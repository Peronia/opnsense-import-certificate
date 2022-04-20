# opnsense-import-certificate
Script to import an SSL certificate into a running opnsense system.
The webgui will be restarted. The last certifcate in the webgui will be overwritten.

## Usage

### Preconditions
Since the last certifcate will be overwritten:
- the last certificate should be the one to be overwritten
- the certificate must be imported manually for the first time

#### Ideally, it looks like this before the script run the first time (system/security/certificates in webgui):
![firefox_vfqVCBtGCs](https://user-images.githubusercontent.com/25049991/164163290-b2e35b81-7273-42a9-baf3-98a335db228f.png)
- first the self-signed certificate (will not be deleted)
- last the certificate that should get overwritten
- the name of the certificate doesn't matter

### In Code
```
php opnsense-import-certificate.php /path/to/certificate.crt /path/to/private/key.pem
```

## Automation example with acme.sh

Create a renew hook:
```
acme.sh --renew --dns dns_provider -d first.dom.ain -d '*.first.dom.ain' --renew-hook "php opnsense-import-certificate.php /path/to/certificate.crt /path/to/private/key.pem"
```
Whenever acme.sh has successfully renewed the certificate, the hook is executed. See the [documentation](https://github.com/acmesh-official/acme.sh/wiki/Using-pre-hook-post-hook-renew-hook-reloadcmd).\
Alternatively, you can use [deployhooks](https://github.com/acmesh-official/acme.sh/wiki/deployhooks).
