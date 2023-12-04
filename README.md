# Feame PHP Framework

Feame is a lightweight PHP framework designed to provide structure and ease of development for web applications. This readme will guide you through the setup, configuration, and usage of the framework.

## File Structure

The file structure of a Feame project is organized for clarity and modularity. Here's an overview of the main directories:
```
C:.
├───!Feame
│ ├───database
│ ├───defaultControllers
│ ├───DoNotTouch
│ ├───models
│ └───routing
├───App
│ ├───Controllers
│ ├───phpTemplates
│ └───Views
│ ├───error
│ └───home
│ ├───en
│ └───nl
└───public
```


- **!Feame**: Core framework files.
- **App**: Application-specific files.
- **public**: Publicly accessible files like index.php and assets.

## Configuration

### `config.php`

The `config.php` file centralizes configuration settings. Adjust the paths and domain according to your setup. Notable configurations include:

- **DOMAIN**: The root domain of your application.
- **ROOT**: The root path of your server.
- **FEAME_PATH**: The path to the Feame framework directory.
- **APP_PATH**: The path to your application directory.
- **CONTROLLER_DEFAULT**: Default controller.
- **METHOD_DEFAULT**: Default method.
- **CONTROLLERS**: Array of controllers.

### `bootstrap.ini.php`

Change the `BASE_DIR` in `bootstrap.ini.php` to match the absolute path to your Feame directory.

## Controllers

Create controllers in the `App/Controllers` directory. Here's an example (`Home.php`):

```
<?php
class Home extends Controller
{
    public $IndexWildcards = [":lang", "?anchor"];

    public function Index($data)
    {
        $data['title'] = "Home";
        $lang = $this->parseLanguage($data);

        Controller::view("home{$lang}/home", $data);
    }

    // ... other methods ...
}
```

## Views

Views are located in the App/Views directory under their name and language. Use simple PHP and HTML:
```
<?php
$template = "layout";
?>
<h1>Hello World</h1>
```

## Templates

Templates are located in the App/phpTemplates directory. They contain the common structure like head, title, etc., and import the corresponding view:
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$data['title']?></title>
</head>
<body>
    <? import($template, $data); ?>
</body>
</html>
```
