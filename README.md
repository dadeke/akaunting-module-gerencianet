# Efí (Brazil) app for Akaunting

[![Release](https://img.shields.io/github/v/release/dadeke/akaunting-module-gerencianet?label=release)](https://github.com/dadeke/akaunting-module-gerencianet/releases)
[![Tests](https://github.com/dadeke/akaunting-module-gerencianet/actions/workflows/tests.yml/badge.svg)](https://github.com/dadeke/akaunting-module-gerencianet/actions)
[![License](https://img.shields.io/github/license/dadeke/akaunting-module-gerencianet?label=license)](LICENSE.txt)

This app allows your customers to pay their invoices with Pix a common payment method used in Brazil using the payment processor [Efí](https://sejaefi.com.br).

## Requirements

- [Akaunting 3](https://github.com/akaunting/akaunting/releases)

## Installation

- Into `modules` create folder `Gerencianet` (camel case)
- Download the last release [https://github.com/dadeke/akaunting-module-gerencianet/releases](https://github.com/dadeke/akaunting-module-gerencianet/releases) **gerencianet-(version).zip**
- Extract zip file into `modules/Gerencianet`
- Run install dependencies: `composer install`
- [Install](https://developer.akaunting.com/documentation/modules/#67474166c92e) the module: `php artisan module:install gerencianet 1`

## Settings

Create your Client ID, Client Secret:  
[https://app.sejaefi.com.br/api/aplicacoes](https://app.sejaefi.com.br/api/aplicacoes)  
and Certificate:  
[https://app.sejaefi.com.br/api/meus-certificados](https://app.sejaefi.com.br/api/meus-certificados)

Save your credentials here:

![Akaunting - Settings - Gerencianet](https://user-images.githubusercontent.com/6050573/214577231-c0d585ce-e860-4a07-af91-5d1f08ab5a72.png)
