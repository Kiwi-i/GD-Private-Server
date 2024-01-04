# GD-Private-Server
## Geometry Dash Private Server
Basically a Geometry Dash Server Emulator

**The primary difference from Cvolton's server is this tries to mimic RobTop's server as much as possible.**

Supported version of Geometry Dash: **1.0 - 2.2**
(See [the backwards compatibility section of this article](https://github.com/Kiwi-i/GD-Private-Server/wiki/Deliberate-differences-from-real-GD) for more information)

Required version of PHP: **5.5+** (tested up to 8.1.2)

### Branches
- master - Primary branch supporting 2.2
***
### Setup
Follow [the setup guide](https://github.com/Kiwi-i/GD-Private-Server/wiki/Setup-guide-for-VPSs-and-Webserver-Hosts)

#### Updating the server
See [README.md in the `_updates`](_updates/README.md)
***
### Credits
Cvolton for 99.9% of the server code

Base for account settings and the private messaging system by someguy28

Using this for XOR encryption - https://github.com/sathoro/php-xor-cipher - (incl/lib/XORCipher.php)

Using this for cloud save encryption - https://github.com/defuse/php-encryption - (incl/lib/defuse-crypto.phar)

Most of the stuff in generateHash.php has been figured out by pavlukivan and Italian APK Downloader, so credits to them
