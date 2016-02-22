# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
apt-get update
apt-get install -y git curl php5-cli php5 php5-intl
curl -Ss https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = 'bento/ubuntu-14.04'
  # config.vm.network "forwarded_port", guest: 80, host: 8085
  # config.vm.network "private_network", ip: "192.168.56.101"
  # config.vm.network :private_network, type: :dhcp
  # config.vm.network "public_network"
  config.vm.hostname = "anymap-geojson"
  config.vm.synced_folder '.', '/var/www/anymap-geojson'
  config.vm.provision 'shell', inline: @script

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end

end
