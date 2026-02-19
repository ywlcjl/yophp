# YoPHP — Lightweight High-Performance PHP MVC Framework

YoPHP is a PHP development framework designed for ultimate performance and simplified logic. It features a powerful Regex routing engine, an intelligent parameter injection mechanism, and out-of-the-box core components.

## 🚀 Core Features

* **Intelligent Routing**: Supports static mapping and Regex dynamic matching with automatic parameter parsing.
* **Reflection-based Injection**: Controller method parameters correspond directly to URL variables, eliminating manual fetching.
* **Security Defense**: Built-in strict path validation, input sanitization, and permission checks.
* **Multi-Driver Cache**: Supports Redis, APCu, and File drivers with automatic failover capabilities.
* **Full Component Suite**: Includes Validator, Upload, Image Processing, Captcha, and Pagination.

## 📂 Project Structure

```text
project/
├── app/
│   ├── controllers/    # controller folder
│   ├── models/         # model folder
│   ├── views/          # view folder
│   └── helpers/        # Custom helper function folder
├── config/             # config (routes.php, config.php)
├── framework/          # core class folder
├── public/             # http folder (index.php, uploads)
```

## 🛠️ Quick Start
1. Route Configuration (config/routes.php)
   You can define very flexible routing rules：
```php
$routes = [
    'news/{id:num}' => 'example/detail',           // match /news/123
    'user/{name:str}/{id:num}' => 'user/profile', // auto get $name and $id
];
```

2. Controller
   By extending YoControllerBase, you gain access to powerful built-in utility features. The framework supports automatic parameter injection, where variables defined in your routes (e.g., {id}) are automatically passed as arguments to your controller methods:
```php
class ExampleController extends YoControllerBase {
    // The {id} variable from the route is automatically assigned to $id
    public function detail($id = 0) {
        $model = ExampleModel::getInstance();
        $data['detail'] = $model->getRow(['id' => $id]);
        view()->render('example/detail', $data);
    }
}
```

3. Model
```php
class ExampleModel extends YoModelBase {
    protected static $_name = 'example'; // Database table name
}
```

4. Cache
```php
// Primary: Redis; Fallback: File cache on failure.
$cache = YoCache::getInstance('redis', 'file');
$cache->save('key', $data, 3600);
```

## 🛡️ Security Recommendations
Ensure the public/ directory is set as the Document Root of your Web Server.

Set DEBUG_MODE to product in config.php for production environments.


## 🔧 Module Documentation
# 1. Routing System
* **Location**: config/routes.php
* **Syntax**
  * {id:num}: id is the variable name; num restricts the input to integers.
  * {name:str}: name is the variable name; str restricts the input to alphabetic strings.
  * {id:any}: id is the variable name; any allows any string input.
```php
$routes = [
    'e' => 'example',                                    // Direct mapping to the index method of the 'example' controller
    'e/{id:num}' => 'example/detail',                    // Mapping with a numeric parameter
    'e/{id:num}/profile' => 'example/json',              // Mapping with a numeric parameter and static suffix
    'e/{id:num}/{name:str}' => 'example/detailWithName', // Mapping with numeric + string parameters
    'e/{id:any}' => 'example/sql',                       // Mapping with any string input
    'e/page' => 'example/page',                          // Direct two-level path mapping
    'json' => 'example/json',                            // Direct single-level path mapping
    'admin' => 'backend/home/index'                      // Mapping to Module / Controller / Method
];
```

# 2. Model Component Usage
YoModelBase provides a fluent, PDO-prepared interface for secure database operations. It automatically handles SQL concatenation and parameter binding, preventing SQL injection at the source.

## (1). Defining a Model
* All models must inherit from YoModelBase.
* **Rule**: You must define protected static $_name (corresponding to the database table name).
* **Mechanism**: The framework manages models using the Singleton Pattern to prevent redundant database connections.
```php
class UserModel extends YoModelBase {
    // Bind to the database table name
    protected static $_name = 'users'; 
}
```
## (2). Obtaining a Model Instance
* Do not use the new keyword. Instead, use the getInstance() method:
```php
$userModel = UserModel::getInstance();
```
## (3). Query Operations (Read)
* A. Fetch Single Record (getRow)
```php
// SELECT * FROM users WHERE id = ? LIMIT 1
$user = $userModel->getRow(['id' => 1]);

// Specify fields
$user = $userModel->getRow(['id' => 1], 'id, username, email');
```
* B. Fetch Data List (getList)
```php
// Parameters: Conditions, Limit, Offset, Order, Fields
$users = $userModel->getList(['status' => 1], 10, 0, 'create_time DESC');
```
* C. Advanced Conditional Queries (buildWhere logic)
* YoModelBase supports including operators directly within the array keys for flexible filtering:
```php
$params = [
    'age >' => 18,              // Comparison operators
    'name like' => 'jacky',     // Fuzzy search (automatically appends % wildcards)
    'id in' => [1, 2, 3],       // IN queries
    'status !=' => 0            // Not equal
];
$list = $userModel->getList($params);
```
## (4). Writing and Updating (Write)
* **A. Insert Data (insert)**
```php
$data = [
    'username' => 'test_user',
    'password' => md5('123456'),
    'create_time' => date('Y-m-d H:i:s')
];
// Returns the ID of the last inserted row
$newId = $userModel->insert($data); 
```
* **B. Update Data (update)**
```php
$setData = ['status' => 1];
$whereData = ['id' => 5];
$userModel->update($setData, $whereData);
```
* **C. Delete Data (delete)**
```php
// Permanently remove the record matching the criteria
$userModel->delete(['id' => 5]);
```
## (5). Pagination System (getPage & getPageSql)
* This is one of YoPHP's most powerful features. It automatically calculates the current page based on the URL offset and generates the necessary data for navigation.
* **Method 1**: Automatic Pagination based on Array Conditions
```php
// Automatically handles pagination parameters from the URL and generates HTML for the View
$result = $userModel->getPage(['status' => 1], 'id DESC', '/user/list', 15);

// $result structure:
// ['list' => Data records, 'total' => Total number of rows, 'current' => Current page number, ...]
```

* **Method 2**: Pagination based on Complex Raw SQL
* If you have complex JOIN or GROUP BY statements, use getPageSql:
```php
$sql = "SELECT u.*, p.profile_name FROM users u LEFT JOIN profile p ON u.id = p.uid";
$result = $userModel->getPageSql($sql, [], '/user/list', 15);
```

## (6). Raw SQL Execution (query)
* Use this method when the built-in fluent methods do not meet your specific requirements:
```php
// Fetch multiple rows
$res = $userModel->query("SELECT * FROM users WHERE id > ?", [10]);

// Fetch a single row (set the third parameter to true)
$res = $userModel->query("SELECT * FROM users LIMIT 1", [], true);
```

# 3. Controller Usage
All business controllers should typically inherit from YoControllerBase. It provides convenient request handling, security filtering, and cookie management features.

## (1). Basic Definition
Controller files are stored in the app/controllers/ directory. By extending the base class, you can directly call built-in secure methods.
```php
class UserController extends YoControllerBase {
    public function login() {
        // Business logic...
    }
}
```
## (2). Securely Obtaining Request Data
There is no need to use $_GET or $_POST directly. The methods provided by the base class have sanitize filtering enabled by default, effectively defending against XSS attacks.
* **A. Fetch GET Parameters**
```php
// Syntax: get($key, $default = '', $isHtmlspecialchars = true)
$id = $this->get('id', 0); // Get 'id', returns 0 if it doesn't exist. HTML filtering enabled by default.
$rawName = $this->get('name', '', false); // Third parameter 'false' performs trim only (no htmlspecialchars).
```
* **B. Fetch POST Parameters**
```php
// Syntax: post($key, $default = '', $isHtmlspecialchars = true)
$username = $this->post('username');
$password = $this->post('password', '', false); // Passwords typically do not require HTML entity conversion.
```
## Cookie Management
* YoPHP's cookie handling automatically integrates security configurations (such as HttpOnly and SameSite) and supports global prefix management.
* **A. Setting a Cookie**
```php
// Syntax: setCookie($name, $value, $expire, $path, $httpOnly)
$this->setCookie('user_token', 'abc123xyz', 3600); // Expires in 1 hour
```
* **Security Features**: In PHP 7.3+ environments, the system automatically sets SameSite=Lax to prevent CSRF attacks and enables the Secure flag based on whether HTTPS is active.

* **Automatic Prefix**: The system automatically prepends the COOKIE_PREFIX to the cookie name.


* **B. Getting and Deleting Cookies**
```php
// Get a cookie (also supports security filtering)
$token = $this->getCookie('user_token');

// Delete a specific cookie
$this->deleteCookie('user_token');

// Clear all cookies that carry the system prefix
$this->clearAllCookies();
```
## Loading Helper Functions (Helpers)
* If you have global functions that aren't universally needed (e.g., email sending, encryption algorithms), you can place them in app/helpers/ and load them as needed.
```php
public function index() {
    // Load app/helpers/StringHelper.php
    $this->loadHelper('StringHelper');
    
    // Once loaded, you can call functions within that file
    $result = formatDate(time()); 
}
```
* **Performance Optimization**: loadHelper uses a static variable cache internally. Multiple calls to the same helper will only trigger one include, reducing disk I/O overhead.

## (5). Best Practices for Controller Development
* **Constructors**: If you define a __construct in a subclass, always consider calling parent::__construct() (even if the current base class constructor is empty, it is good practice).


* **Method Visibility**: Only public methods can be directly accessed via routing.


* **Logic Layering**: Keep controllers "Thin" (Thin Controller). Delegate complex business logic and database operations to Models.

# 4. View Component
YoView is responsible for rendering data processed by the controller into HTML templates or outputting data to the client in JSON format. It features powerful built-in pagination logic and supports custom language settings (English/Chinese) and CSS styles.

## (1). Basic Usage
In the controller, you can access the View object via the global helper function view() (which internally calls YoView::getInstance()).

* **A. Variable Assignment (assign)**
* You can pass variables to the template one by one:
```php
view()->assign('title', 'Welcome to YoPHP');
view()->assign('user', $userData);
```
* **B. Rendering a View (render)**
* Renders the main page, typically called at the end of a controller method.
```php
// Parameter 1: View filename (relative to app/views/, without .php)
// Parameter 2: Data array (higher priority than assign)
view()->render('home/index', ['status' => 'success']);
```
* Note: render() can only be called once per request cycle. To load multiple view fragments (like headers or footers), use loadView().
* **C. Loading View Fragments (loadView)**
* Used to include one view within another.
```php
// Inside app/views/home/index.php
<?php view()->loadView('common/header'); ?>
<h1><?php echo $title; ?></h1>
<?php view()->loadView('common/footer'); ?>
```
## (2). JSON Output
For API requests, use the json() method. It automatically sets the response headers and terminates program execution.
```php
public function api() {
    $data = ['code' => 200, 'msg' => 'ok', 'data' => [1, 2, 3]];
    view()->json($data); 
}
```
## (3). Pagination Navigator
This is a highlight of the YoPHP View layer. It not only calculates the logic but also generates ready-to-use HTML code.

* **A. Triggering Pagination in the Controller**
* Usually triggered automatically by the Model layer's getPage() method.
```php
// The Model will internally call view()->pageDefaultCn(...)
$result = $userModel->getPage($params, 'id DESC', '/user/list');
```
* **B. Displaying Pagination in the View**
* In your template file, use getPageNav() to output the generated HTML:
```php
<div class="content">
    <?php foreach($list as $item): ?> ... <?php endforeach; ?>
</div>

<div class="pagination-wrapper">
    <?php echo view()->getPageNav(); ?>
</div>
```
* **C. Customizing Pagination Styles and Language**
    * Default English: Call pageDefault().

    * Chinese Support: Call pageDefaultCn() (Displays: 首页, 上一页, 下一页, 尾页).

    * Full Customization: Call createPageNav() to define custom HTML class names, making it easy to integrate with CSS frameworks like Bootstrap or Tailwind.
# (4). Core Logic Analysis
* **Variable Extraction (extract)**
    * YoView internally uses PHP's extract() function. This means if you define
    * $data['my_name'] = 'Jacky'; 
    * in the controller, you can access it directly as $my_name in the view file, which is highly intuitive.

* **Render Protection Mechanism**
    * The $_isRendered flag ensures that developers do not accidentally output two complete HTML documents in a single request. This rigorous design avoids garbled pages and layout conflicts.

* **Flexible Pagination Calculation**
    * The createPageNav method supports a "Sliding Window" pagination list (defaulting to 7 page links). When there are many pages, it automatically calculates the start and end indices so the current page remains centered, providing an excellent user experience.

# 5. Cache Component
YoCache provides a unified caching interface for the framework, supporting three storage mediums: Redis, APCu, and File.
## (1). Core Features
* **Dual-Driver Fault Tolerance**: Supports primary and secondary driver configuration with automatic connection exception handling.

* **Unified Interface**: Consistent code logic regardless of whether the backend is Redis or a local file.

* **Automatic Serialization**: Redis and File drivers automatically handle the serialization of complex objects and arrays.

## (2). Initializing the Cache
You must specify a primary driver and a backup driver.
```php
// Example: Prioritize Redis; if the connection fails, fall back to File cache.
$cache = YoCache::getInstance('redis', 'file');

// You can check the currently active driver:
echo "Current Driver: " . $cache->_currentDriver;
```
## (3). Basic Operations
* **A. Save Cache (save)**
```php
// Syntax: save($id, $data, $ttl = 60)
// $id: Unique cache key
// $data: Data to store (supports strings, arrays, objects)
// $ttl: Time-to-live (seconds), default is 60s
$cache->save('user_list', $users, 3600);
```
* **B. Get Cache (get)**
  Returns false if the cache does not exist or has expired.
```php
$data = $cache->get('user_list');

if ($data === false) {
    // Cache miss: fetch from database
    $data = $userModel->getList();
    $cache->save('user_list', $data, 3600);
}
```
* **C. Delete/Clear Cache (delete / clear)**
```php
// Delete a single key
$cache->delete('user_list');

// Clear all cache under the current driver
$cache->clear();
```
## (4). Driver Specifications

YoPHP utilizes a Strategy Pattern design, supporting a multi-driver fallback mechanism.

| Driver Name | Requirements | Features | Use Cases                                       |
| :--- | :--- | :--- |:------------------------------------------------|
| **Redis** | `php-redis` extension | Ultra-fast, supports distribution, and data persistence. | High-concurrency and multi-server environments. |
| **APCu** | `apcu` extension | In-memory storage, zero network overhead, the "king" of single-node performance. | High-performance single-machine requirements.   |
| **File** | `app/cache/` Writable | No extra services required, high compatibility, but slower disk I/O. | Basic environments, development/debugging, and as a final backup solution.                                                |

## (5). Advanced Technique: Failover Demonstration
The internal __construct logic of YoPHP follows enterprise standards:
```php
try {
    // Attempt to load Redis
    $this->_cache = $this->_loadDriver('redis');
} catch (Exception $e) {
    // Redis error: automatically switch to the File driver
    $this->_cache = $this->_loadDriver('file');
}
```
* **Developer Tip**:
  * In production, it is recommended to set the primary driver to redis or apcu and the backup driver to file. This ensures that even if the Redis server goes down, your website won't crash due to cache errors—it will simply run slightly slower using file caching.

## Important Notes
* **File Permissions**: Ensure the app/cache/ directory has read/write permissions when using the file driver.


* **Serialization**: The Redis driver has SERIALIZER_PHP enabled by default, allowing you to store PHP arrays directly without manual json_encode.


* **Key Security**: The file driver uses md5($id) to generate filenames, preventing security issues caused by illegal characters in paths.

# 6. Validator Component
YoValidator is used to validate the integrity and legality of user-submitted form data ($_POST). It supports method chaining, custom error messages, and a variety of built-in validation rules.

## (1). Core Features
* **Method Chaining**: Intuitive syntax like $v->rule()->required()->run() makes logic clear at a glance.


* **Template Support**: Supports {label} and {param} placeholders for dynamic error message generation.


* **Automatic Sanitization**: Automatically performs trim() on strings during the validation process.


* **Early Exit**: Utilizes a "Circuit Breaker" pattern—validation stops and returns immediately upon the first error found, improving response speed.


## (2). Basic Usage
You can use it in your controller like this:
```php
public function save() {
    $v = new YoValidator();
    
    // Define rules
    $v->rule('username', 'Username')->required()->alphaNumeric()->minLength(4);
    $v->rule('email', 'Email Address')->required()->email();
    $v->rule('age', 'Age')->numeric();

    // Execute validation
    if ($v->run()) {
        // Validation passed, get sanitized data
        $safeData = $v->getData();
        // Proceed with database operations...
    } else {
        // Validation failed, get the first error message
        $error = $v->getErrorInfo();
        view()->json(['status' => 'error', 'msg' => $error]);
    }
}
```
## 3. Custom Error Messages
You can define specific error messages for specific rules on a per-field basis:
```php
$v->rule('password', 'Password')
  ->required()
  ->minLength(6)
  ->message([
      'required' => 'Hey, do not forget your {label}!',
      'minLen'   => 'The {label} is too short; it needs at least {param} characters.'
  ]);
```
## (4). Built-in Validation Rules Reference

| Method | Description                               | Example                                 |
| :--- |:------------------------------------------|:----------------------------------------|
| **required()** | Mandatory field; cannot be empty          | `$v->rule('name', 'Name')->required()`  |
| **email()** | Must be a valid email format              | `$v->rule('mail', 'Email')->email()`    |
| **numeric()** | Must be a number or numeric string        | `$v->rule('price', 'Price')->numeric()` |
| **minLength($n)** | Minimum length restriction                | `->minLength(5)`                        |
| **maxLength($n)** | Maximum length restriction                | `->maxLength(20)`                       |
| **alpha()** | Alphabetic characters only                | `->alpha()`                             |
| **alphaNumeric()** | Letters and numbers only                  | `->alphaNumeric()`                      |
| **alphaDash()** | Letters, numbers, underscores, and dashes | `->alphaDash()`                         |
| **url()** | Must be a valid URL                       | `->url()`                               |
| **ip()** | Must be a valid IP address                                          | `->ip()`                                |

## (5). Error Template Placeholders
* The default error templates are defined in the $messages property. You can use the following placeholders:

* {label}: Corresponds to the second parameter of the rule() method (the field name).

* {param}: Corresponds to the parameter passed to the validation method (e.g., 6 in minLength(6)).


* Global Message Modification
    * To change the system-wide messages to English or another style:
```php
$v->setMessages([
    'required' => '{label} is mandatory!',
    'email'    => 'Wrong format for {label}'
]);
```
## (6). Security Recommendations
* **Mandatory Backend Validation**: While HTML5 can perform basic checks, never trust client-side data. Always use YoValidator as a backend fail-safe.

* **Use Sanitized Data**: After validation, use $v->getData() instead of accessing $_POST directly, as getData() returns data that has been cleaned (trimmed).


# 7. Upload Component
YoUpload is a lightweight, high-security file upload utility. It supports automatic date-based directory partitioning, filename encryption, and rigorous file authenticity verification.

## (1). Core Features
* **Deep Fraud Detection**: Utilizes the PHP finfo extension to inspect the file's binary stream, preventing malicious file uploads.


* **Automatic Directory Management**: Automatically creates subdirectories in Y/m/d format to prevent performance degradation caused by having too many files in a single folder.


* **Filename Encryption**: Defaults to converting filenames into unique 32-character MD5 strings to prevent overwriting and Chinese character encoding issues.


* **Comprehensive Feedback**: Provides a full suite of information after a successful upload, including relative paths, absolute paths, and file sizes.


## (2). Basic Usage
* **A. Simple Upload**
    * Initialize and execute the upload in your controller as follows:
```php
$config = [
    'uploadPath'   => './uploads/images', // Physical root directory
    'allowedTypes' => 'jpg|png|gif',      // Allowed extensions
    'maxSize'      => 2048,               // Limit to 2MB (in KB)
    'encryptName'  => true                // Rename file to a hash
];

$upload = new YoUpload($config);

if ($upload->doUpload('user_avatar')) {
    // Get detailed upload information
    $fileInfo = $upload->getData();
    echo "Upload successful! Storage path: " . $fileInfo['relativePath'];
} else {
    // Get error message
    echo "Upload failed: " . $upload->getError();
}
```
* **B. getData() Return Values**
  * Upon success, getData() returns an associative array perfect for database storage:

  | Key | Description | Example Value |
    | :--- | :--- | :--- |
  | **fileName** | Stored filename | `a2b3c4...d5.jpg` |
  | **subPath** | Date-based subdirectory | `2026/02/19/` |
  | **relativePath** | **Path recommended for DB storage** | `uploads/images/2026/02/19/xxx.jpg` |
  | **fileSize** | File size (KB) | `150.25` |
  | **fileMime** | Real MIME type | `image/jpeg` |

## (3). Configuration Parameters

| Option             | Type | Default | Description                                                    |
|:-------------------| :--- | :--- |:---------------------------------------------------------------|
| **uploadPath**     | `string` | `''` | **Required** Sets the physical starting path for storage.      |
| **allowedTypes**   | `string` | `'png\|jpg\|jpeg\|gif'` | Allowed extensions, separated by `\|`  |
| **maxSize**        | `int` | `2048` | Maximum upload limit in **KB**。                                             |
| **encryptName**    | `bool` | `true` | Whether to rename the file. If false, keeps the original name (sanitized).                            |
| **fileNameSuffix** | `string` | `''` | Adds a fixed prefix to the encrypted filename.                                                 |

## (4). Security Mechanism Analysis
* **Deep MIME Verification**
    * Traditional checks only look at $_FILES['file']['type'], which is sent by the browser and easily spoofed. YoUpload logic is as follows:
* **YoUpload logic is as follows**:

    * Checks if the extension is in the allowedTypes list.

    * Core Step: Uses finfo to read the actual binary content and compares it against a MIME mapping table (isMimeAllowed).

    * If a PHP script is disguised as a .jpg, the validation will fail with: "File content does not match the extension."

* **Directory Creation**
    * The code uses mkdir($fullPath, 0777, true). The third parameter true enables recursive mode, meaning if uploads/ exists but 2026/02/19/ does not, it will automatically build the entire path hierarchy.

## (5). View Layer Example (HTML)
* Ensure your form includes the enctype="multipart/form-data" attribute:
```php
<form action="/user/upload" method="post" enctype="multipart/form-data">
    <input type="file" name="user_avatar" />
    <button type="submit">Start Upload</button>
</form>
```
# 8. Image Component
YoImage is designed for efficient, high-quality image processing. It currently focuses on thumbnail generation and supports mainstream formats including JPG, PNG, and GIF.

## (1). Core Features
* **High-Quality Resampling**: Uses bicubic interpolation for scaling to prevent aliasing and blurring.


* **Smart Proportional Scaling**: Supports Maintain Ratio functionality to prevent image distortion.


* **Transparency Preservation**: Perfect support for alpha channel processing in PNG images.


* **Memory Management**: Automatically destroys image handles to prevent server memory exhaustion when processing large batches of images.


## (2). Basic Usage
* **A. Generate a Thumbnail**
* You can specify the maximum width and height, and the system will automatically calculate the optimal proportions.
```php
$img = new YoImage('./uploads/2026/02/19/original.jpg');

// Generate a thumbnail with a maximum size of 200x200
// By default, it creates 'original_thumb.jpg' in the same directory
if ($img->thumb(200, 200, 'thumb')) {
    echo "Thumbnail generated successfully!";
} else {
    echo "Error: " . $img->getError();
}
```
* **B. Overwrite Original Image**
  * If you do not need to keep the original file, simply set the thumbName to an empty string:
```php
$img->setSource('./uploads/temp.png');
$img->thumb(100, 100, '', 90); // 100x100, quality 90, directly overwrites the source
```
## (3). Method Parameter Details
* thumb() Method
* This is the core method of the class. The parameters are defined as follows:

| Parameter | Type | Default | Description                             |
| :--- | :--- | :--- |:----------------------------------------|
| **$width** | `int` | **Required** | Maximum target width.                   |
| **$height** | `int` | **Required** | Maximum target height.                                        |
| **$thumbName** | `string` | `'thumb'` | Suffix. The generated file becomes filename_suffix.ext. If empty, overwrites the source. |
| **$quality** | `int` | `100` | Image quality (1-100).                           |
| **$maintainRatio** | `bool` | `true` | Whether to maintain proportions. If false, the image is forced to the specified dimensions.          |

## (4). Using with YoUpload
* In actual development, thumbnails are usually generated immediately after a successful file upload:
```php
$upload = new YoUpload($config);
if ($upload->doUpload('avatar')) {
    $data = $upload->getData();
    
    // Instantiate the image class
    $image = new YoImage($data['fullPath']);
    // Generate a 150x150 avatar thumbnail
    $image->thumb(150, 150, 'avatar_small');
}
```
## (5). Technical Implementation Details
* **PNG Quality Handling**
* In the output() method, the library automatically handles the difference between JPEG and PNG quality parameters:
    * JPEG: Accepts a quality value from 0-100.
    * PNG: Automatically converts the 0-100 quality scale to the 0-9 compression level required by the GD library (where higher numbers result in smaller files).

* Transparency Protection
* When processing PNGs, the code executes critical operations to ensure backgrounds do not turn black:
```php
imagealphablending($dstImg, false); // Disable blending
imagesavealpha($dstImg, true);      // Save full alpha channel information
```

# 9. Captcha Component Usage Guide
YoCaptcha is used to generate automated graphical verification codes. By incorporating random noise and interference lines, it effectively prevents malicious attacks from automated scripts.

## (1). Core Features
* **Zero-Dependency Execution**: Uses the PHP GD library's built-in fonts, eliminating the need for complex font path configurations.

* **Confusion-Resistant Character Set**: Automatically excludes visually similar characters (e.g., 0, 1, O, l) to improve user experience.

* **High Security**: Built-in random noise and irregular interference lines increase the difficulty of OCR (Optical Character Recognition) identification.

* **Native Output**: Automatically handles HTTP headers, allowing for one-line display on the frontend.

## (2). Basic Usage
* **A. Generate and Display Captcha**
    * Create a method in your Controller specifically to output the captcha image:
```php
public function captcha() {
    // Initialize (Width, Height, Character Count)
    $captcha = new YoCaptcha(120, 40, 4);
    
    // 1. Get the generated code (convert to lowercase and store in Session for later verification)
    $code = $captcha->getCode();
    $_SESSION['captcha_code'] = $code;
    
    // 2. Render and output the image directly
    $captcha->doImage();
}
```
* **B. Verify Captcha**
    * Compare the input in your form submission logic:
```php
public function doLogin() {
    $userInput = strtolower($this->post('captcha'));
    $savedCode = $_SESSION['captcha_code'];

    if ($userInput !== $savedCode) {
        die("Incorrect captcha code!");
    }
    // Continue with business logic...
}
```
## (3). Methods and Parameters
__construct() Constructor

| Parameter | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| **$width** | `int` | `120` | The width of the captcha image (in pixels). |
| **$height** | `int` | `40` | The height of the captcha image (in pixels). |
| **$codeNum** | `int` | `4` | The number of characters to generate. |

* **Core Methods**
    * **getCode()**: Returns the currently generated captcha string (lowercase). It must be called after or within the drawing cycle as it relies on the internal generation trigger.

    * **doImage()**: Executes the drawing logic. This includes setting the background, drawing noise, rendering interference lines, filling the text, and sending the image/png HTTP Header.

## (4). Styling Details
* **Noise Density**：Generates 100 random-colored pixels by default.

* **Interference Lines**：Generates 5 randomly positioned lines across the image.

* **Character Pool**：23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ (Characters like 0, 1, o, l, and I have been excluded to prevent user error).

## (5). Frontend Implementation Example
* In HTML, simply point the src attribute of an <img> tag to your controller's captcha method:

```php
<div class="form-group">
    <input type="text" name="captcha" placeholder="Enter captcha">
    <img src="/auth/captcha" onclick="this.src='/auth/captcha?'+Math.random()" style="cursor:pointer;" alt="Captcha">
</div>
```

# 10. Disclaimer and Usage Recommendations
* **Disclaimer**: This project assumes no responsibility for any network or software security issues encountered in production environments. If you choose to deploy this framework in a production setting, you do so at your own risk and are solely responsible for addressing any issues or outcomes that may arise.


* **Code Review**: The YoPHP framework is built on the principles of clarity and simplicity. Before using it for production-grade products, it is strongly recommended that you personally review the entire source code within the framework/ directory. Comprehensive usage demonstrations are available in the controllers/ExampleController.php file.


* **Performance**: YoPHP offers performance nearly identical to native PHP. In standard benchmark tests, its QPS (Queries Per Second) performance is typically 10x or higher than that of other mainstream heavy-duty PHP frameworks.


* **Environment Requirements**:
  * **Minimum Requirement**: **PHP 7.0+**

  * **Recommended**: **PHP 8.0** or later for optimal performance and access to modern language features.

© 2026 ywlcjl. Licensed under the MIT License.
