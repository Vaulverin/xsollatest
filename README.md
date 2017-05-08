# xsollatest
This is an test API for xsolla. API provides creating user, sending file, updating file, listing files, getting meta data from file.

## Installation:
1. download vagrant box with `vagrant box add phalconphp/xenial64`
2. add `192.168.50.4 xsollatest.dev` to your hosts file
3. run `vagrant up`
4. run `vagrant ssh`
5. execute command `./setup.sh` from `~/workspace/xsollatest/`

## API:
API use https protocol only.
### */create-user*
Creates user.
- method: POST
- headers: Content-Type=`application/x-www-form-urlecoded`
- params: `name=<userName>&password=<password>`
- returns: token for authorization

### */file/filename*
Creates file with filename.
- method: POST
- headers: Authorization=user-token
- content: file contents
- returns: success

### */file/filename*
Updates file with filename.
- method: PUT
- headers: Authorization=user-token
- content: file contents
- returns: success

### */file/filename/meta*
Gets meta data from filename.
- method: GET
- headers: Authorization=user-token
- returns: file meta data

### */list*
Gets user files.
- method: GET
- headers: Authorization=user-token
- returns: files list

## Project info
This test project based on Phalcon framework, cause it's simple, fast and light.

Main files are:
- `app/app.php` - handles requests
- `app/models/File.php` - works with files
- `app/models/User.php` - works with users
