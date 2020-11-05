# Builder

A simple application builder that will install all composer dependencies accordingly. This way
you will not need them in your version control (which is necessary when you will work with a
scalable architecture).

## Build the application

You can use the builder as follows. Use -d to specify the target directory, otherwise the current
working directory will be used.

```shell script
php installer.php -d ./cscart/
```