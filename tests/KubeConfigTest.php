<?php

namespace RenokiCo\PhpK8s\Test;

use RenokiCo\PhpK8s\Exceptions\KubeConfigClusterNotFound;
use RenokiCo\PhpK8s\Exceptions\KubeConfigContextNotFound;
use RenokiCo\PhpK8s\Exceptions\KubeConfigUserNotFound;
use RenokiCo\PhpK8s\KubernetesCluster;

class KubeConfigTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        KubernetesCluster::setTempFolder(__DIR__.DIRECTORY_SEPARATOR.'temp');
    }

    public function test_kube_config_from_yaml_file_with_base64_encoded_ssl()
    {
        $this->cluster->fromKubeConfigYamlFile(__DIR__.'/cluster/kubeconfig.yaml', 'minikube');

        [
            'verify' => $caPath,
            'cert' => $certPath,
            'ssl_key' => $keyPath,
        ] = $this->cluster->getClient()->getConfig();

        $this->assertEquals("some-ca\n", file_get_contents($caPath));
        $this->assertEquals("some-cert\n", file_get_contents($certPath));
        $this->assertEquals("some-key\n", file_get_contents($keyPath));
    }

    public function test_kube_config_from_yaml_file_with_paths_to_ssl()
    {
        $this->cluster->fromKubeConfigYamlFile(__DIR__.'/cluster/kubeconfig.yaml', 'minikube-2');

        [
            'verify' => $caPath,
            'cert' => $certPath,
            'ssl_key' => $keyPath,
        ] = $this->cluster->getClient()->getConfig();

        $this->assertEquals('/path/to/.minikube/ca.crt', $caPath);
        $this->assertEquals('/path/to/.minikube/client.crt', $certPath);
        $this->assertEquals('/path/to/.minikube/client.key', $keyPath);
    }

    public function test_kube_config_from_yaml_cannot_load_if_no_cluster()
    {
        $this->expectException(KubeConfigClusterNotFound::class);

        $this->cluster->fromKubeConfigYamlFile(__DIR__.'/cluster/kubeconfig.yaml', 'minikube-without-cluster');
    }

    public function test_kube_config_from_yaml_cannot_load_if_no_user()
    {
        $this->expectException(KubeConfigUserNotFound::class);

        $this->cluster->fromKubeConfigYamlFile(__DIR__.'/cluster/kubeconfig.yaml', 'minikube-without-user');
    }

    public function test_kube_config_from_yaml_cannot_load_if_wrong_context()
    {
        $this->expectException(KubeConfigContextNotFound::class);

        $this->cluster->fromKubeConfigYamlFile(__DIR__.'/cluster/kubeconfig.yaml', 'inexistent-context');
    }
}