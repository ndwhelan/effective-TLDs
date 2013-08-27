effective-TLDs
==============

PHP code to build TLD Suffix set from publicsuffix.org, as well as a class for determining a domain's public suffix. Handles wildcards and exception cases.  Correctly gives the public suffix for omg.blogspot.com.au (``'omg.blogspot.com.au'``), omg.com.au (``'omg.com.au'``), and omg.au (``null``), whereas other implementations in PHP either have trouble or onerous licensing.

### Installation

Download the files and place them somewhere in your PHP include path. Alternatively, specify a fully qualified path in your includes.
```php
require_once('class.EffectiveDomain.php');
```

### Use

```php
echo EffectiveDomain::getPublicSuffix("omg.hillgod.com")."\n";
```
```bash
$> php myScript.php
hillgod.com
$>
```

### Updating TLDs

``updateEffectiveTLDs.php`` is included to build ``effectiveTLDs.inc.php`` from the latest list of public suffixes at [Publicsuffix.org](http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1). This link can be modified in ``updateEffectiveTLDs.php``.  

```bash
$> php updateEffectiveTLDs.php > effectiveTLDs.inc.php
```
