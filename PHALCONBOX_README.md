# Phalcon Box

Phalcon strives to get closer to developers, providing flexible, powerful and simple tools.
We would like a Phalcon development process to be effortless and pleasant from deployment of the development environment
to the programming in [Zephir](https://github.com/phalcon/zephir) language.

----------

Phalcon Box is an official, pre-packaged Vagrant box that provides you a wonderful development environment without
requiring you to install PHP, a web server, and any other server software on your local machine.

[Vagrant](http://vagrantup.com/) provides a simple, elegant way to manage and provision Virtual Machines and this is a
_recommended_ Vagrant setup to get loaded with core development tools to build a powerful PHP application focused on
[Phalcon Framework](https://phalconphp.com/).

[Join us on Slack](https://slack.phalconphp.com/) to chat with other Phalcon contributors!

## Contents

- [Overview](#overview)
  - [Requirements](#requirements)
  - [Packages Included](#packages-included)
- [Install](#install)
  - [Installing the Vagrant Box](#installing-the-vagrant-box)
  - [Installing the Phalcon Box](#installing-the-phalcon-box)
  - [Configuring](#configuring)
    - [Memory and CPU](#memory-and-cpu)
    - [Shared folders](#shared-folders)
    - [Nginx sites](#nginx-sites)
    - [Configuring the `hosts` file](#configuring-the-hosts-file)
  - [Launching the Phalcon Box](#launching-the-phalcon-box)
- [Daily usage](#daily-usage)
  - [Accessing Phalcon Box globally](#accessing-phalcon-box-globally)
  - [Connecting via SSH](#connecting-via-ssh)
  - [Connecting to databases](#connecting-to-databases)
  - [Adding additional sites](#adding-additional-sites)
  - [Ports](#ports)
    - [Forwarding additional ports](#forwarding-additional-ports)
  - [Sharing your environment](#sharing-your-environment)
  - [Network interfaces](#network-interfaces)
  - [Updating Phalcon Box](#updating-phalcon-box)
  - [Provider specific settings](#provider-specific-settings)
    - [VirtualBox](#virtualBox)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Overview

We use the default **phalcon/xenial64** box from [Vagrant Cloud](https://atlas.hashicorp.com/phalconphp/boxes/xenial64/)
for compatibility. If you choose to use a 64-bit ISO you may need to update your BIOS to enable
[virtualization](https://en.wikipedia.org/wiki/X86_virtualization) with `AMD-V`, `Intel VT-x` or `VIA VT`.

When you provision Vagrant for the first time it's always the longest procedure (`vagrant up`). Vagrant will download
the entire Linux OS if you've never used Vagrant or the `phalconphp/xenial64` Box. Afterwards, booting time is fast.

### Requirements

* Operating System: Windows, Linux, or OSX
* [Virtualbox](https://www.virtualbox.org/wiki/Downloads) >= 5.1
* [Vagrant](https://www.vagrantup.com/downloads.html) >= 1.9

### Packages Included

* Ansible
* Beanstalkd
* Blackfire
* Composer
* Git
* goreplace
* Mailhog
* Memcached
* MongoDB
* MySQL
* Nginx
* Ngrok
* Node.js (with Yarn, Bower, Grunt, and Gulp)
* PHIVE
* PHP 7.1
* PHPMD
* PHP_CodeSniffer
* Phalcon
* Phing
* PostgreSQL
* Redis
* Sqlite3
* Ubuntu 16.04
* Zephir

## Install

### Installing the Vagrant Box

Once VirtualBox and Vagrant have been installed, you should add the `phalconphp/xenial64` box to your Vagrant
installation using the following command in your terminal. It will take a few minutes to download the box, depending
on your Internet connection speed:

```bash
vagrant box add phalconphp/xenial64
```

If this command fails, make sure your Vagrant installation is up to date.

### Installing the Phalcon Box

You may install Phalcon Box by simply cloning the repository. Consider cloning the repository into a `workspace`
folder within your "home" directory, as the Phalcon Box box will serve as the host to all of your Phalcon projects:

```bash
cd ~
git clone https://github.com/phalcon/box.git workspace
```

The `master` branch will always contain the latest stable version of Phalcon Box. If you wish to check older versions
or newer ones currently under development, please switch to the relevant branch/tag.

You can find the latest stable version on the [Github Release Page](https://github.com/phalcon/box/releases):

```bash
# Clone the desired release...
git checkout v2.1.0
```

Once you have cloned the Phalcon Box repository, run the install command from the Phalcon Box root directory to
create the `settings.yml` configuration file. The `settings.yml` file will be placed in the Phalcon Box directory:

```bash
# macOS || Linux
bash install
```

```cmd
rem Windows
install.bat
```

Now you are ready to provision your Virtual Machine, run:

```bash
vagrant up
```

### Configuring

#### Memory and CPU

By default this setup uses 2GB RAM. You can change this in `settings.yml` and simply run `vagrant reload`:

```yaml
memory: 4096
```

You can also use more than one core if you like, simply change this line in the same file:

```yaml
cpus: 4
```

#### Shared folders

The `folders` property of the `settings.yml` file lists all of the folders you wish to share with your
Phalcon Box environment. As files within these folders are changed, they will be kept in sync between your local
machine and the Phalcon Box environment. You may configure as many shared folders as necessary:

```yaml
folders:
    - map: ~/workspace
      to: /home/vagrant/workspace
```

To enable [NFS](https://www.vagrantup.com/docs/synced-folders/nfs.html), just add a simple flag to your synced folder
configuration:

```yaml
folders:
    - map: ~/workspace
      to: /home/vagrant/workspace
      type: "nfs"
```

You may also pass any options supported by Vagrant's
[Synced Folders](https://www.vagrantup.com/docs/synced-folders/basic_usage.html) by listing them under the `options` key:

```yaml
folders:
    - map: ~/workspace
      to: /home/vagrant/workspace
      type: "nfs"
      options:
            rsync__args: ["--verbose", "--archive", "--delete", "-zz"]
            rsync__exclude: ["node_modules"]
```

**NOTE:** macOS users probably will need to install `vagrant-bindfs` plugin to fix shared folder (NFS) permission issue:

```bash
vagrant plugin install vagrant-bindfs
```

#### Nginx sites

The `sites` property allows you to easily map a "domain" to a folder on your Phalcon Box environment. A sample site
configuration is included in the `settings.yml` file. You may add as many sites to your Phalcon Box environment as
necessary. Phalcon Box can serve as a convenient, virtualized environment for every Phalcon project you are working on:

```yaml
sites:
    - map: phalcon.local
      to:  /home/vagrant/workspace/phalcon/public
```

You can use the `type` parameter to specify the type of Nginx configuration for the site. For example:

```yaml
sites:
    - map:  landing.local
      to:   /home/vagrant/workspace/landing/public
      type: spa
```

The default type is `phalcon`.

If the desired type is not allowed `phalcon` will be used as fallback.

Available types:

* `phalcon`
* `slayer`
* `proxy`
* `spa`
* `silverstripe`
* `symfony2`
* `statamic`
* `laravel`

Feel free to suggest a new type of Nginx configuration
[through opening a New Feature Request](https://github.com/phalcon/box/issues/new).

If you change the `sites` property after provisioning the Phalcon Box, you should re-run `vagrant reload --provision`
to update the Nginx configuration on the virtual machine.

#### Configuring the `hosts` file

You must add the "domains" for your Nginx sites to the hosts file on your machine. The hosts file will redirect requests
for your Phalcon sites into your Phalcon Box machine. On Mac and Linux, this file is located at `/etc/hosts`.
On Windows, it is located at `C:\Windows\System32\drivers\etc\hosts`. The lines you add to this file will look like the
following:

```
192.168.50.4  phalcon.local
```

Make sure the IP address listed is the one set in your `settings.yml` file. Once you have added the domain to your
`hosts` file and launched the Vagrant box you will be able to access the site via your web browser:

```
http://phalcon.local
```

**NOTE:** To enable adding new sites to the `hosts` file automatically use `vagrant-hostsupdater` plugin:

```bash
vagrant plugin install vagrant-hostsupdater
```

### Launching the Phalcon Box

Once you have edited the `settings.yml` to your liking, run the `vagrant up` command from your Phalcon Box directory
(for example `$HOME/workspace`). Vagrant will boot the virtual machine and automatically configure your shared folders
and Nginx sites.

To destroy the machine, you may use the `vagrant destroy --force` command.

## Daily usage

### Accessing Phalcon Box globally

Sometimes you may want to `vagrant up` your Phalcon Box machine from anywhere on your filesystem. You can do this on
Mac or Linux systems by adding a [Bash function](http://tldp.org/HOWTO/Bash-Prog-Intro-HOWTO-8.html) to your Bash
profile. On Windows, you may accomplish this by adding a "batch" file to your `PATH`. These scripts will allow you
to run any Vagrant command from anywhere on your system and will automatically point that command to your Phalcon Box
installation:

**Mac || Linux**

```bash
function box()
{
    ( cd $HOME/workspace && vagrant $* )
}
```

**NOTE:** Make sure to tweak the `$HOME/workspace` path in the function to the location of your actual Phalcon Box
installation. Once the function is installed, you may run commands like `box up` or `box ssh` from anywhere on your
system.

**Windows**

Create a `box.bat` batch file anywhere on your machine with the following contents:

```cmd
@echo off

set cwd=%cd%
set box=C:\workspace

cd /d %box% && vagrant %*
cd /d %cwd%

set cwd=
set box=
```

**NOTE:** Make sure to tweak the example `C:\workspace` path in the script to the actual location of your Phalcon Box
installation. After creating the file, add the file location to your `PATH`. You may then run commands like
`box up` or `box ssh` from anywhere on your system.

### Connecting via SSH

You can SSH into your virtual machine by issuing the `vagrant ssh` terminal command from your Phalcon Box directory.

But, since you will probably need to SSH into your Phalcon Box machine frequently, consider adding the "function"
[described above](#accessing-phalcon-box-globally) to your host machine to quickly SSH into the Phalcon Box.

### Connecting to databases

To connect to your MySQL, Postgres or MongoDB database from your host machine's database client, you should connect to
`127.0.0.1` and port `33060` (MySQL), `54320` (Postgres) or `27017` (MongoDB). The username and password for databases
is `phalcon` / `secret`.

**NOTE:** You should only use these non-standard ports when connecting to the databases from your host machine.
You will use the default `3306` and `5432` ports in your Phalcon database configuration file since Phalcon is running
within the Virtual Machine.

To access to the interacive db console from Phalcon Box type:

- **Postgres:** `psql -U phalcon -h localhost` (password `secret`)
- **MySQL:** `mysql` (password not needed for CLI tool)
- **MongoDB:** `mongo` (password not needed for CLI tool)

### Adding additional sites

Once your Phalcon Box environment is provisioned and running, you may want to add additional Nginx sites for your
applications. You can run as many Phalcon projects as you wish on a single Phalcon Box environment. To add an additional
site, simply add the site to your `settings.yml` file:

```yaml
sites:
    - map: phalcon.local
      to:  /home/vagrant/workspace/phalcon/public
    - map: pdffiller.local
      to:  /home/vagrant/workspace/pdffiller/public
    - map: blog.local
      to:  /home/vagrant/workspace/blog/public
```

If Vagrant is not managing your "hosts" file automatically, you may need to add the new site to that file as well:

```
192.168.50.4  phalcon.local
192.168.50.4  pdffiller.local
192.168.50.4  blog.local
```

**NOTE:** To enable adding new sites to the `hosts` file automatically use `vagrant-hostsupdater` plugin:

```bash
vagrant plugin install vagrant-hostsupdater
```

Once the site has been added, run the `vagrant reload --provision` command from your Phalcon Box directory.

### Ports

By default, the following ports are forwarded to your Phalcon Box environment:

| Forfarded port | Phalcon Box | Host system |
| --- | --- | --- |
| **SSH** | `22` | `2222` |
| **HTTP** | `80` | `8000` |
| **HTTPS** | `443` | `44300` |
| **MySQL** | `3306` | `33060` |
| **Postgres** | `5432` | `54320` |
| **Mailhog** | `8025` | `8025` |

#### Forwarding additional ports

If you wish, you may forward additional ports to the Phalcon Box, as well as specify their protocol:

```yaml
ports:
    - send: 50000
      to: 5000
    - send: 7777
      to: 777
      protocol: udp
```

### Sharing your environment

Sometimes you may wish to share what you're currently working on with coworkers or a client. Vagrant has a built-in way
to support this via `vagrant share`; however, this will not work if you have multiple sites configured in your
`settings.yml` file.

To solve this problem, Phalcon Box includes its own `share` command. To get started, SSH into your Phalcon Box machine
via `vagrant ssh` and run `share <your-site-here>`, for example: `share blog.local`. This will share your site from your
`settings.yml` configuration file. Of course, you may substitute any of your other configured sites for `blog.local`:

```bash
share blog.local
```

After running the command, you will see an [Ngrok](https://ngrok.com) screen appear which contains the activity log and
the publicly accessible URLs for the shared site. If you would like to specify a custom region, subdomain, or other
Ngrok runtime option, you may add them to your `share` command:

```bash
share blog.local -region=eu -subdomain=phalcongelist
```

**NOTE:** Remember, Vagrant is inherently insecure and you are exposing your virtual machine to the Internet when
running the `share` command.

### Network interfaces

The `networks` property of the `settings.yml` configures network interfaces for your Phalcon Box environment.
You may configure as many interfaces as necessary:

```yaml
networks:
    - type: "private_network"
      ip: "192.168.50.99"
```

To enable a [bridged](https://www.vagrantup.com/docs/networking/public_network.html) interface, configure a `bridge`
setting and change the network type to `public_network`:

```yaml
networks:
    - type: "private_network"
      ip: "192.168.50.99"
      bridge: "en1: Wi-Fi (AirPort)"
```

To enable [DHCP](https://www.vagrantup.com/docs/networking/public_network.html), just remove the `ip` option from this
configuration:

```yaml
networks:
    - type: "private_network"
      bridge: "en1: Wi-Fi (AirPort)"
```

### Updating Phalcon Box

You can update Phalcon Box in two simple steps.

1. First, you should update the Vagrant box using the `vagrant box update` command:
  ```bash
  vagrant box update
  ```
2. Next, you need to update the Phalcon Box source code. If you cloned the repository you can simply
  ```bash
  git pull origin master
  ```
at the location you originally cloned the repository.

The new version of Phalcon Box will contain updated or amended configuration files:
* `settings.yml`
* `.bash_aliases`
* `after_provision.sh`

When you run the command `bash install` (or `install.bat`) the Phalcon Box creates these files in the root directory.
However, if the files already exist, they will not be overwritten.

We recommend that you always take backups of those files, and remove them from the project so that the new updated ones
can be copied over. You can then compare your own files with the phalcon box ones to apply your personalized changes and
take advantage of the new features offered by the update.

### Provider specific settings

#### VirtualBox

By default, Phalcon Box configures the `natdnshostresolver` setting to `on`. This allows Phalcon Box to use your host
operating system's DNS settings. If you would like to override this behavior, add the following lines to your
`settings.yml` file:

```yaml
natdnshostresolver: off
```

## Troubleshooting

**Problem:**

> An error occurred in the underlying SSH library that Vagrant uses.
> The error message is shown below. In many cases, errors from this
> library are caused by ssh-agent issues. Try disabling your SSH
> agent or removing some keys and try again.
> If the problem persists, please report a bug to the net-ssh project.
> timeout during server version negotiating

**Solution:**

```bash
vagrant plugin install vagrant-vbguest
```

**Problem:**

> Vagrant was unable to mount VirtualBox shared folders. This is usually
  because the filesystem "vboxsf" is not available. This filesystem is
  made available via the VirtualBox Guest Additions and kernel module.
  Please verify that these guest additions are properly installed in the
  guest. This is not a bug in Vagrant and is usually caused by a faulty
  Vagrant box. For context, the command attempted was:
>
> mount -t vboxsf -o uid=900,gid=900 vagrant /vagrant

**Solution:**

```bash
vagrant plugin install vagrant-vbguest
```

## License

Phalcon Box is open source software licensed under the New BSD License.
See the LICENSE.txt file for more. <br>
Copyright (c) 2011-2017, Phalcon Framework Team
