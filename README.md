# CI Bootstrap

### Install Package dependencies

```sh
$ composer update
```

### Install NodeJS dependencies

```sh
$ npm install
```

### Running module migrations

```sh
$ php index.php cli module migrate
```

### Running all unit tests

```sh
$ php index.php cli test all
```

### Running specific module unit tests

```sh
$ php index.php cli test module name
```
