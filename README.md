# PHPCI-Rocketeer

A rocketeer plugin for PHPCI so PHPCI can deploy your code after a succesful build.

The commits on GitHub pull-requests will not be deployed, only commits merged into the main repository will be deployed
if the branch is matching one branchses in the config.

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
