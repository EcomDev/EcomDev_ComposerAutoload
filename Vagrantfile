require 'json'
require 'erubis'
require 'vagrant/util/deep_merge'

data_loader = Proc.new do
  current_path = File.realpath(File.dirname(__FILE__));
  files_to_search = []
  Dir.chdir(current_path) do
    files_to_search << Dir.glob('magento.json')
    files_to_search << Dir.glob('magento.*.json')
  end

  magento_json = {}

  files_to_search.flatten!.uniq!

  files_to_search.each do |file|
    eruby = Erubis::Eruby.new(File.read(File.join(current_path, file)))
    json_content = JSON.parse(eruby.result(binding()))

    magento_json = Vagrant::Util::DeepMerge.deep_merge(magento_json, json_content) do |key, old_value, new_value|
      return_value = new_value
      if old_value.is_a?(Array)
        return_value = []
        old_value.each { |v| return_value << v }
        if new_value.is_a?(Array)
          return_value = new_value
        else
          return_value << new_value
        end
      end
      return_value
    end

  end

  magento_json
end

Vagrant.configure("2") do |config|
  magento_json = data_loader.call

  if Vagrant.has_plugin?("vagrant-berkshelf")
    config.berkshelf.enabled = true
  end

  if Vagrant.has_plugin?("vagrant-omnibus")
    config.omnibus.chef_version = :latest
  end

  if Vagrant.has_plugin?("vagrant-hostmanager")
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.include_offline = true
  end

  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :box
    config.cache.auto_detect = true
  end

  if magento_json['magento']['application'].key?('uid') && magento_json['magento']['application']['uid'] === ':auto'
    magento_json['magento']['application']['uid'] = Process.euid
  end

  # Box that is used for chef
  # Debian 7.4
  #config.vm.box = 'opscode-debian-7.4'
  #config.vm.box_url = 'http://opscode-vm-bento.s3.amazonaws.com/vagrant/virtualbox/opscode_debian-7.4_chef-provisionerless.box'
  # Possible options are:
  ## Ubuntu 12.04
  # config.vm.box = "opscode-ubuntu-12.04"
  # config.vm.box_url = "https://opscode-vm-bento.s3.amazonaws.com/vagrant/virtualbox/opscode_ubuntu-12.04_chef-provisionerless.box"
  ## CentOS 6.5
  config.vm.box = "opscode-centos-6.5"
  config.vm.box_url = "https://opscode-vm-bento.s3.amazonaws.com/vagrant/virtualbox/opscode_centos-6.5_chef-provisionerless.box"

  config.vm.network :private_network, ip: magento_json['vm']['ip']
  config.vm.hostname = magento_json['magento']['application']['name']

  skip_vagrant_dir = false
  mount_options = {}

  magento_json['vm']['mount_dir_options'].each do |key, value|
    mount_options[key.to_sym] = value;
  end

  magento_json['vm']['mount_dirs'].each do |local, guest|
    skip_vagrant_dir = true if local == '.'
    config.vm.synced_folder local, guest, mount_options
  end

  unless skip_vagrant_dir
    config.vm.synced_folder ".", "/vagrant", :disabled => true
  end


  cpus = magento_json['vm']['cpu']
  mem = magento_json['vm']['memory']

  # Try to detect CPU and memory automatically if static flag is not set
  unless magento_json['vm'].key?('static') && magento_json['vm']['static']
    host = RbConfig::CONFIG['host_os']

    # Give VM 1/4 system memory & access to all cpu cores on the host
    if host =~ /darwin/
      cpus = `sysctl -n hw.ncpu`.to_i
      # sysctl returns Bytes and we need to convert to MB
      mem = `sysctl -n hw.memsize`.to_i / 1024 / 1024 / 4
    elsif host =~ /linux/
      cpus = `nproc`.to_i
      # meminfo shows KB and we need to convert to MB
      mem = `grep 'MemTotal' /proc/meminfo | sed -e 's/MemTotal://' -e 's/ kB//'`.to_i / 1024 / 4
    end
  end

  config.vm.provider :virtualbox do |vb|
    vb.customize ['modifyvm', :id, '--memory', mem, '--cpus', cpus]
    vb.customize ['modifyvm', :id, '--natdnshostresolver1', 'on']
  end

  config.vm.provision :chef_solo do |chef|
    chef.json = {
        magento: magento_json['magento'],
        php: magento_json['php']
    }
    chef.run_list = magento_json['recipes'].map {|v| "recipe[#{v}]"}
  end

  domain_aliases = [magento_json['magento']['application']['main_domain']]
  domain_aliases << magento_json['magento']['application']['domain_map'].keys
  if magento_json['magento']['application']['domains'].is_a?(Array)
    domain_aliases << magento_json['magento']['application']['domains']
  end

  domain_aliases.flatten!.uniq!

  if Vagrant.has_plugin?("vagrant-hostmanager")
    config.hostmanager.aliases = domain_aliases
  end
end
