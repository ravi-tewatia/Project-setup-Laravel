<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<h1>Laravel BaseCode</h1>

<h2>Setup</h2>

<h3>Checkout Process</h3>

- git config --global user.name "Administrator"
- git config --global user.email "admin@example.com"
- git clone http://192.168.1.175/root/laravel_base.git laravel_base
- composer install

<p></p>

<h3>Please follow below steps after checkout:</h3>

- Create database with <b>utf8mb4_unicode_ci</b> encoding. example: <b>laravel</b>
- Go to the config/database.php and change default MySQL engine to 'InnoDB'
- Open Terminal and go to directory in which you have checkout the project and run: php artisan migrate
- copy "pre-commit" file which is in root directory and place it inside your project's .git/hooks folder
    - exa: C:\wamp64\www\laravel_base\\.git\hooks
- Perform <b>composer global require squizlabs/php_codesniffer</b> (open Windows terminal and paste this, Do not do this VS CODE terminal)
- Perform <b>npm install -g eslint</b> (open Windows terminal and paste this, Do not do this VS CODE terminal)
- Perform <b>npm install -g stylint</b> (open Windows terminal and paste this, Do not do this VS CODE terminal)

<p></p>

<h2>Editor Basic Setup<br/>====</h2>

<b>Visual Studio Code</b>

- Click on the <b>CRLF button</b> found at the <b>bottom-right in VS Code</b>.
- Select <b>LF</b>  at the top
- Install below extensions from <b>VS code editor extensions and follow its respective stpes</b>.
    - <b>phpcs v1.0.5 +</b> : For PHP code validation
    - <b>eslint v2.1.14 +</b> : For JS code validation
        - Make sure to "Enable everywhere" setting for eslint
    - <b>stylint v0.1.3 +</b> : For CSS code validation
- Right click on any installed extension or click GEAR icon followed by extension name
    - Select <b>Extension Settings</b>
    - Click on any "Edit in settings.json" link<br/>
    - Add / Edit below lines in opened settings.json file<br/>
        <pre>
        "phpcs.standard": "PSR2",
        "files.eol": "\n",
        "eslint.validate": [
            "vue",
            "html",
            "javascript"
        ],
        </pre>

    - Run <b>eslint --init</b> in VS terminal and generate scenarios like below
    <pre>
        √ How would you like to use ESLint? · style       
        √ What type of modules does your project use? · none
        √ Which framework does your project use? · none
        √ Does your project use TypeScript? · No
        √ Where does your code run? · browser
        √ How would you like to define a style for your project? · guide
        √ Which style guide do you want to follow? · standard
        √ What format do you want your config file to be in? · JSON
        √ Would you like to install them now with npm? · Yes
    </pre>
- Restart the VS code editor.
- If Above steps does not work, </b>
    - Press "<b>Command + ,</b>"
    - Select User Settings and locate <b>PHP CodeSnipper</b>
    - Scroll to <b>Executable Path</b> and put<br>
        <b>/Users/your-username/.composer/vendor/bin/phpcs</b>
- Also check the "PROBLEMS" tab of the VS Code to check / resolve the issues.
- Enable php cs fixer extension and then do "alt + shift + f" to format the file

<p></p>

<h2>Features/Road Map<br/>======</h2>

- [x] Basic Auth
- [x] Query Logging in File
- [x] Exception Logging in File
- [x] Auto Login/Logout from other tabs
- [x] ESLint
- [ ] Stylint (We have done VS code validation but Pre Commit hook on client side is pending)
- [x] PHP CodeSniffer (i.e. phpcs)
- [x] Git Pre Commit Client Hook with phpcs, stylist & eslint support
- [ ] Git Server Side Hook (We have tried pre-receive on GitLab server but git commands didn't worked there so need to check with IT team)
- [x] CRUD using livewire with search, sorting & export (<b>It's done but not going to add in master branch. If you need this checkout feature-7 branh.</b>)
- [x] Activity Log Model
- [ ] Session timeout prompt
- [ ] User's Session Listing & logout from all/any active session
- [ ] Two Factor with SMS
- [ ] Rate Limiter Listing
- [ ] Cron Jobs
- [ ] Custom Permissions
- [ ] Website Daily Summery Report
- [ ] Simple API JWT/Auth Token with all VERB type (i.e. PUT, GET, POST, DELETE etc.)
- [x] API Log (provision to bypass authentication and test)
- [ ] Ip Restriction for certain api's
- [ ] Different Language provision
- [ ] Settings Api
- [ ] Documentation to track user journey
- [ ] Maintnance Page, Setting page to enable and disable flag




<b>Postmen Link : (https://www.getpostman.com/collections/673c619a1ef23817a742)</b>
<h1></h1>
## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
