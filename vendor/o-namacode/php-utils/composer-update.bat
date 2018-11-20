@echo off
color 0a
composer upgrade
composer update
composer dump-autoload -o