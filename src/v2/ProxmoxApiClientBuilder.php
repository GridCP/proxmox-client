<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ProxmoxApiClientBuilder
{
    private readonly ClientInterface $httpClient;
    private readonly RequestFactoryInterface $requestFactory;
    private readonly StreamFactoryInterface $streamFactory;
    /** @var Plugin[] */
    private array $plugin = [];

    public function __construct(
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function addPlugin(Plugin $plugin): void
    {
        $this->plugin[] = $plugin;
    }

    public function removePlugin(string $pluginFqcn): void
    {
        $this->plugin = array_filter($this->plugin, function (Plugin $plugin) use ($pluginFqcn) {
            return false === ($plugin instanceof $pluginFqcn);
        });
    }

    public function getHttpClient(): HttpMethodsClient
    {
        $pluginClient = new PluginClient($this->httpClient, $this->plugin);

        return new HttpMethodsClient(
            $pluginClient,
            $this->requestFactory,
            $this->streamFactory,
        );
    }
}
