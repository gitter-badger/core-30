<a href="https://docs/freesewing.org/"><img src="https://docs.freesewing.org/img/logo-black.svg" align="right" width=200 style="max-width: 20%;" /></a>
[![Build Status](https://travis-ci.org/freesewing/core.svg?branch=master)](https://travis-ci.org/freesewing/core)

# Freesewing core
[Freesewing](https://freesewing.org/) is an online platform to draft sewing patterns based on your measurements.

> This is our core repository, which holds the platform and the sewing patterns we maintain.

For all info on what freesewing does/is/provides, please check the freesewing documentation at [docs.freesewing.org](https://docs.freesewing.org).

## System Requirements
* PHP 5.6 (we recommend PHP 7)
* composer

## Installation

Full install instructions are available at [docs.freesewing.org/install](https://docs.freesewing.org/install)
but here's the gist of it:

### From packagist
```
composer create-project freesewing/core freesewing
composer dump-autoload -o
```

### From GitHub
```
git clone git@github.com:freesewing/core.git freesewing
composer install
composer dump-autoload -o
```

## License
Our code is licensed [GPL-3](https://www.gnu.org/licenses/gpl-3.0.en.html), 
our patterns and documentation are licensed [CC-BY](https://creativecommons.org/licenses/by/4.0/).

## Contribute

Your pull request are welcome here. 

If you're interested in contributing, a good place to start is 
[our Slack channel](https://docs.freesewing.org/slack/) 
where you can ask questions, meet other freesewers, 
or just hang out and share a laugh.
