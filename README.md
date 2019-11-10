# Composer dependency analyzer

Tool for fetching and comparing your composer dependencies with released packages' versions.

Allowing you to check dependencies by composer file on [github](https://github.com) or use it locally with mounted file. 

## Usage
- Until there will be pre-built docker image you need to clone the repository.
- Enter folder with code and build image locally 
    ```
    docker build -t composer-dependency-analyzer .
    ```
- When build is done you may check if your dependencies are up to date
    - Fetching `composer.json` from github
        ```
        docker run --rm -v $(pwd):/code -v composer-dependency-checker ./bin/console.php composer:dependencies:analyze <repository> <branch>
        ```
        - `<repository>` need to be full path e.g. `s3tezsky/composer-dependency-checker` _(optional)_
        - `<branch>` name of git branch you want to check (`master` is set as default) _(optional)_
    - When no repository is specified the analyzer will check `/code/input/` folder. When you want to test local composer file you need to mount it into container. 
        ```
        docker run --rm -v $(pwd):/code -v $(pwd)/../another-project:/code/input composer-dependency-checker ./bin/console.php composer:dependencies:analyze
        ```
- Output will look like this
    ```
    +------------------------------+----------+----+---------------+
    | package                      | actual   |    | upgradable to |
    +------------------------------+----------+----+---------------+
    | guzzlehttp/guzzle            | ^6.4     | -> | 6.4.1         |
    | phpstan/phpstan-shim         | ^0.11.19 | -> | 0.11.19       |
    | phpunit/phpunit              | ^8.4     | -> | 8.4.3         |
    | slevomat/coding-standard     | ^5.0     | -> | 5.0.4         |
    | squizlabs/php_codesniffer    | ^3.5     | -> | 3.5.2         |
    | symfony/config               | ^4.3     | -> | v4.3.6        |
    | symfony/console              | ^4.3     | -> | v4.3.6        |
    | symfony/dependency-injection | ^4.3     | -> | v4.3.6        |
    | symfony/http-kernel          | ^4.3     | -> | v4.3.6        |
    | symfony/yaml                 | ^4.3     | -> | v4.3.6        |
    +------------------------------+----------+----+---------------+
    ```

Enjoy!
