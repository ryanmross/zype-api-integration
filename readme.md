# Zype API MRSS Generator

Creates an MRSS feed to your specifications using the Zype video api.


Needs api_config.ini file with Zype API keys (currently looks for this in the parent folder).

Find the API keys from your Zype account and create api_config.ini that looks like this:

```
;Account credentials from zype
[Account]

admin_key = PUT ADMIN KEY HERE
read_key = PUT READ KEY HERE
```

OPML folder is a place to create outline files that group different MRSS feeds together.

Uses PHP-CURL-CLASS:
https://github.com/php-curl-class/php-curl-class