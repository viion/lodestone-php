# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"

  # provision
  config.vm.provision :shell, path: "./provision.sh"

  # provider
  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.memory = 512
    vb.cpus = 1
  end

  # network
  config.vm.network "private_network", ip: "#{1 + rand(255)}.#{1 + rand(255)}.#{1 + rand(255)}.#{1 + rand(255)}"
  config.vm.hostname = "lodestone.div"
  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true

  # sync folder
  config.vm.synced_folder ".", "/vagrant", type: "nfs"
end
