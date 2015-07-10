# [PHPCI](https://www.phptesting.org/)-[Rocketeer](http://rocketeer.autopergamene.eu/)

A rocketeer plugin for PHPCI so PHPCI can deploy your code after a succesful build.

The commits on GitHub pull-requests will not be deployed, only commits merged into the main repository will be deployed
if the branch is matching one branches in the config.

### Install the plugin

1. Navigate to your PHPCI root directory and run `composer require enrise/phpci-rocketeer`
1. Update your `phpci.yml` in the project you want to deploy with

### Make sure that

1. The `rocketeer` shell command is globally accessable on your PHPCI server ([see how](http://rocketeer.autopergamene.eu/#/docs/docs/I-Introduction/Getting-started))
1. The *php-ci-cronjob-server-user* can actually can run the `rocketeer deploy` command

### PHPCI config

```yml
rocketeer:
    deploy:
        <git_branch>:
            connection: <connection_name>
            stage: <stage_name>
```

example:

```yml
complete:
    rocketeer:
        deploy:
            master:
                connection: phpci
                stage: production
            develop:
                connection: phpci
                stage: develop
```
