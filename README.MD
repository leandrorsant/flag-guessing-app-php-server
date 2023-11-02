# Flag guessing app php server
PHP REST API for [Flag Guessing App React](https://github.com/leandrorsant/flag-guessing-app-react), which fetches data from a MongoDB atlas database to allow creation of user accounts and highscores.

# Requirements
You need a working PHP server with MongoDB libs. More information can be found at [MongoDB Docs](https://www.mongodb.com/docs/php-library/current/).

# Get started
Clone this repository
```
git clone https://github.com/leandrorsant/flag-guessing-app-react

```

Create a env.php file at the project's root directory with the following contents:
```
<?php

const MONGODB_URL = <Your MongoDB Connection URL>;
```
Replace the text between brackets with your MongoDB URL.



