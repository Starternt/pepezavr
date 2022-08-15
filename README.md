# About

Этот сервис отвечает за пользователей, посты и контент, который они создают.

1. Symfony 5.3
2. PHP 8
3. PostgreSQL 13.2

# Quick Start
1. `make init`
2. `make up`
3. Execute `lexik:jwt:generate-keypair` command to generate key-pair
4. Execute `doctrine:fixtures:load -n` command to load fixtures

# Useful makefile commands

1. `make console` - default shell is zsh
2. `make test` - PHPUnit tests
3. `make cs` - PHP CS-fixer
4. `make psalm` - Psalm

Ссылки на связанные модули:
- https://github.com/Starternt/pepe-notifications-service сервис уведомлений

TODO:
* Добавить интеграцию с сервисом уведомлений
* Добавить возможность голосовать за посты
* Добавить кролика и реализовать отправку уведомлений
* Реализовать модуль отправки сообщений в телеграм бот
* Реализовать алгоритм выдачи постов пользователю
* Юнит тесты
* Отладить развёртывание проекта с нуля
* Рассмотреть перенос аутентификации в отдельный сервис
