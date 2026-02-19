# YoPHP — 轻量级高性能 PHP MVC 框架

YoPHP 是一个为追求极致性能和简单逻辑而设计的 PHP 开发框架。它拥有强大的正则路由引擎、智能的参数注入机制以及开箱即用的核心组件。

## 🚀 核心特性

* **智能路由系统**：支持静态映射与正则动态匹配，参数自动解析。
* **反射参数注入**：控制器方法参数直接对应 URL 变量，无需手动获取。
* **安全防御**：内置严格的路径校验、输入过滤（Sanitize）及权限检查。
* **多驱动缓存**：支持 Redis、APCu、File 驱动，并支持驱动故障自动回退。
* **功能全家桶**：内置验证器（Validator）、文件上传（Upload）、图像处理（Image）、验证码（Captcha）及分页（Pagination）。

## 📂 项目结构

```text
project/
├── app/
│   ├── controllers/    # 控制器目录
│   ├── models/         # 模型目录
│   ├── views/          # 视图目录
│   └── helpers/        # 自定义辅助函数
├── config/             # 配置文件 (routes.php, config.php)
├── framework/          # 框架核心源码
├── public/             # 外部入口 (index.php, uploads)
```

## 🛠️ 快速上手
1. 路由配置 (config/routes.php)
你可以定义非常灵活的路由规则：
```php
$routes = [
    'news/{id:num}' => 'example/detail',           // 匹配 /news/123
    'user/{name:str}/{id:num}' => 'user/profile', // 自动提取参数 $name 和 $id
];
```

2. 控制器 (Controller)
通过继承 YoControllerBase，你可以获得强大的辅助功能：
```php
class ExampleController extends YoControllerBase {
    // 路由中的 {id} 会自动赋值给 $id
    public function detail($id = 0) {
        $model = ExampleModel::getInstance();
        $data['detail'] = $model->getRow(['id' => $id]);
        view()->render('example/detail', $data);
    }
}
```

3. 模型 (Model)
```php
class ExampleModel extends YoModelBase {
    protected static $_name = 'example'; // 对应数据库表名
}
```

4. 缓存使用
```php
// 优先使用 Redis，失败则回退到文件缓存
$cache = YoCache::getInstance('redis', 'file');
$cache->save('key', $data, 3600);
```

## 🛡️ 安全建议
确保 public/ 目录为 Web 服务器的 Document Root。
生产环境下将 config.php 中的 DEBUG_MODE 设置为 product。


## 🔧 模块说明
# 1. routes 设置
* 路由文件位置: config/routes.php 
  * {id:num}, id为变量名称,num为整数, 
  * {name:str}, name为变量名称,str为字符串. 
  * {id:any}, any为任意字符串输入.
```php
$routes = [
    'e' => 'example',                  //直接映射控制器example的index方法
    'e/{id:num}' => 'example/detail',  //带数值映射
    'e/{id:num}/profile' => 'example/json',  //带数值+映射
    'e/{id:num}/{name:str}' => 'example/detailWithName', //数值+字符串
    'e/{id:any}' => 'example/sql',     //任意字符串输入
    'e/page' => 'example/page',        //直接两层映射
    'json' => 'example/json',          //直接一层映射
    'admin' => 'backend/home/index'    //映射到模块目录 / 控制器 /方法
];
```

# 2. Model 组件使用
YoModelBase 提供了一套流式的、基于 PDO 预处理的安全数据库操作接口。它能自动处理 SQL 拼接和参数绑定，从源头上防止 SQL 注入。

## (1). 定义模型
* 所有模型必须继承 YoModelBase。
* **规则**：必须定义 protected static $_name（对应数据库表名）。
* **机制**：框架通过单例模式管理模型，避免多次连接数据库。
```php
class UserModel extends YoModelBase {
    // 绑定数据库表名
    protected static $_name = 'users'; 
}
```
## (2). 获取模型实例
* 不要使用 new 关键字，请使用 getInstance()：
```php
$userModel = UserModel::getInstance();
```
## (3). 查询操作 (Read)
* **A. 获取单条数据 (getRow)**
```php
// SELECT * FROM users WHERE id = ? LIMIT 1
$user = $userModel->getRow(['id' => 1]);

// 指定字段
$user = $userModel->getRow(['id' => 1], 'id, username, email');
```
* **B. 获取列表 (getList)**
```php
// 参数：条件, 限制数量, 起始位置, 排序, 字段
$users = $userModel->getList(['status' => 1], 10, 0, 'create_time DESC');
```
* **C. 高级条件查询 (buildWhere 逻辑)**
  * YoModelBase 支持在数组键名中加入操作符：
```php
$params = [
    'age >' => 18,              // 比较运算符
    'name like' => 'jacky',     // 模糊查询 (自动补全 %)
    'id in' => [1, 2, 3],       // IN 查询
    'status !=' => 0            // 不等于
];
$list = $userModel->getList($params);
```
## (4). 写入与更新 (Write)
* **A. 插入数据 (insert)**
```php
$data = [
    'username' => 'test_user',
    'password' => md5('123456'),
    'create_time' => date('Y-m-d H:i:s')
];
$newId = $userModel->insert($data); // 返回最后插入的 ID
```
* **B. 更新数据 (update)**
```php
$setData = ['status' => 1];
$whereData = ['id' => 5];
$userModel->update($setData, $whereData);
```
* **C. 删除数据 (delete)**
```php
$userModel->delete(['id' => 5]);
```
## (5). 分页系统 (getPage & getPageSql)
* 这是 YoPHP 最强大的功能之一，它能自动根据 URL 偏移量计算当前页。
* **方式一：基于数组条件的自动分页**
```php
// 会自动处理 URL 中的分页参数，并生成 HTML 给 View
$result = $userModel->getPage(['status' => 1], 'id DESC', '/user/list', 15);

// $result 结构：
// ['list' => 数据列表, 'total' => 总行数, 'current' => 当前页码, ...]
```

* **方式二：基于复杂原生 SQL 的分页**
* 如果你有复杂的 JOIN 或 GROUP BY 语句，使用 getPageSql：
```php
$sql = "SELECT u.*, p.profile_name FROM users u LEFT JOIN profile p ON u.id = p.uid";
$result = $userModel->getPageSql($sql, [], '/user/list', 15);
```

## (6). 原生 SQL 执行 (query)
* 当内置方法无法满足需求时：
```php
// 获取多行
$res = $userModel->query("SELECT * FROM users WHERE id > ?", [10]);

// 获取单行 (第三个参数设为 true)
$res = $userModel->query("SELECT * FROM users LIMIT 1", [], true);
```

# 3. Controller 使用
所有业务控制器通常应继承 YoControllerBase。它为你提供了便捷的请求处理、安全过滤以及 Cookie 管理功能。

## (1). 基础定义
   控制器文件存放在 app/controllers/ 目录下。通过继承基类，你可以直接调用内置的安全方法。
```php
class UserController extends YoControllerBase {
    public function login() {
        // 业务逻辑...
    }
}
```
## (2). 安全获取请求数据
   不需要直接使用 $_GET 或 $_POST。基类提供的方法默认开启了 sanitize 过滤，能有效防御 XSS 攻击。
* **A. 获取 GET 参数**
```php
// 语法：get($key, $default = '', $isHtmlspecialchars = true)
$id = $this->get('id', 0); // 获取 id，不存在则返回 0，默认开启 HTML 过滤
$rawName = $this->get('name', '', false); // 第三个参数为 false 则只执行 trim，不执行 htmlspecialchars
```
* **B. 获取 POST 参数**
```php
// 语法：post($key, $default = '', $isHtmlspecialchars = true)
$username = $this->post('username');
$password = $this->post('password', '', false); // 密码通常不需要转义 HTML 实体
```
## (3). Cookie 管理
* YoPHP 的 Cookie 处理自动集成了安全性配置（如 HttpOnly 和 SameSite），并支持全局前缀管理。
* **A. 设置 Cookie**
```php
// 语法：setCookie($name, $value, $expire, $path, $httpOnly)
$this->setCookie('user_token', 'abc123xyz', 3600); // 1小时后过期
```
* **安全特性**：在 PHP 7.3+ 环境下，系统会自动设置 SameSite=Lax 以防御 CSRF 攻击，并根据当前是否为 HTTPS 自动开启 Secure 标志。

* **自动前缀**：系统会自动给名称加上 COOKIE_PREFIX 前缀。


* **B. 获取与删除 Cookie**
```php
// 获取 Cookie (同样支持安全过滤)
$token = $this->getCookie('user_token');

// 删除指定 Cookie
$this->deleteCookie('user_token');

// 清理所有带系统前缀的 Cookie
$this->clearAllCookies();
```
## (4). 加载辅助函数 (Helpers)
* 如果你有一些全局不通用的函数（如邮件发送、加密算法），可以放在 app/helpers/ 下并按需加载。
```php
public function index() {
    // 加载 app/helpers/StringHelper.php
    $this->loadHelper('StringHelper');
    
    // 加载后即可调用其中的函数
    $result = formatDate(time()); 
}
```
* **性能优化**：loadHelper 内部使用了 static 变量缓存，多次调用同一个 Helper 只会执行一次 include，减少磁盘 I/O 开销。
## (5). 控制器开发规范建议
* 构造函数：如果你在子类中定义了 __construct，请务必考虑是否需要调用 parent::__construct()（虽然目前基类构造函数为空，但这是一个良好的编程习惯）。


* **方法可见性**：只有 public 方法可以被路由直接访问。


* **逻辑分层**：尽量让 Controller 保持轻量（Thin Controller），将复杂的业务逻辑和数据库操作通过 Model 处理。

# 4. View 视图说明
YoView 负责将控制器处理好的数据渲染到 HTML 模板中，或者以 JSON 格式输出给客户端。它内置了强大的分页生成逻辑，支持自定义中英文及 CSS 样式。

## (1). 基础调用
   在控制器中，你可以通过全局辅助函数 view()（其内部调用 YoView::getInstance()）来访问视图对象。

* **A. 模板赋值 (assign)**
* 你可以逐个为模板传递变量：
```php
view()->assign('title', '欢迎来到 YoPHP');
view()->assign('user', $userData);
```
* **B. 渲染视图 (render)**
* 渲染主页面，通常在控制器的最后调用。
```php
// 参数 1：视图文件名（相对于 app/views/，不带 .php）
// 参数 2：直接传递的数据数组（优先级高于 assign）
view()->render('home/index', ['status' => 'success']);
```
* 注意：render() 在一个请求周期内只能调用一次。如果需要加载多个视图片段（如页头、页脚），请使用 loadView()。
* **C. 加载视图片段 (loadView)**
* 用于在一个视图中包含另一个视图。
```php
// 在 app/views/home/index.php 中
<?php view()->loadView('common/header'); ?>
<h1><?php echo $title; ?></h1>
<?php view()->loadView('common/footer'); ?>
```
## (2). JSON 输出
对于 API 请求，使用 json() 方法。它会自动设置 Header 并结束程序运行。
```php
public function api() {
    $data = ['code' => 200, 'msg' => 'ok', 'data' => [1, 2, 3]];
    view()->json($data); 
}
```
## (3). 分页导航器 (Pagination)
这是 YoPHP 视图层的亮点功能。它不仅计算逻辑，还直接生成可用的 HTML 代码。

* **A. 在控制器中触发分页**
* 通常由 Model 层的 getPage() 方法自动触发。
```php
// Model 内部会自动调用 view()->pageDefaultCn(...)
$result = $userModel->getPage($params, 'id DESC', '/user/list');
```
* **B. 在视图中显示分页**
* 在 app/views/ 对应的模板文件中，使用 getPageNav() 获取生成的 HTML：
```php
<div class="content">
    <?php foreach($list as $item): ?> ... <?php endforeach; ?>
</div>

<div class="pagination-wrapper">
    <?php echo view()->getPageNav(); ?>
</div>
```
* **C. 自定义分页样式与语言**
  * **默认英文**：调用 pageDefault()。

  * **中文支持**：调用 pageDefaultCn()（显示：首页、上一页、下一页、尾页）。

  * **完全自定义**：你可以调用 createPageNav() 自定义 HTML 的 class 类名，方便对接不同的 CSS 框架（如 Bootstrap 或 Tailwind）。
# (4). 视图层核心逻辑解析
* **变量提取 (extract)**
  * YoView 内部使用了 PHP 的 extract() 函数。这意味着你在控制器中定义的：
  * $data['my_name'] = 'Jacky';
  * 在视图文件中可以直接通过变量 $my_name 访问，非常符合直觉。

* **渲染保护机制**
  * $_isRendered 标志位确保了开发者不会意外地在一次请求中输出两个完整的 HTML 文档。这种严谨的设计能有效避免页面乱码和布局混乱。

* **灵活的分页计算**
  * createPageNav 方法支持“滑动窗口”分页列表（默认显示 7 个页码）。当页数过多时，它会自动计算起始和结束页码，始终让当前页处于中间位置，用户体验极佳。

# 5. Cache 缓存组件说明
YoCache 为框架提供了统一的缓存接口，支持 Redis、APCu 和 File（文件） 三种存储介质。
## (1). 核心特性
* **双驱动容错**：支持主从驱动配置，自动处理连接异常。

* **统一接口**：无论底层是 Redis 还是文件，代码逻辑完全一致。

* **自动序列化**：Redis 驱动和 File 驱动会自动处理复杂对象/数组的序列化。

## (2). 初始化缓存
   你需要指定一个主驱动和一个备用驱动。
```php
// 示例：优先使用 Redis，如果 Redis 连接失败则使用文件缓存
$cache = YoCache::getInstance('redis', 'file');

// 你可以通过查看当前正在使用的驱动
echo "Current Driver: " . $cache->_currentDriver;
```
## (3). 基础操作
* **A. 存储缓存 (save)**
```php
// 语法：save($id, $data, $ttl = 60)
// $id: 缓存唯一键名
// $data: 要存储的数据（支持字符串、数组、对象）
// $ttl: 有效期（秒），默认为 60 秒
$cache->save('user_list', $users, 3600);
```
* **B. 获取缓存 (get)**
  如果缓存不存在或已过期，将返回 false。
```php
$data = $cache->get('user_list');

if ($data === false) {
    // 缓存失效，从数据库读取
    $data = $userModel->getList();
    $cache->save('user_list', $data, 3600);
}
```
* **C. 删除缓存 (delete / clear)**
```php
// 删除单个键
$cache->delete('user_list');

// 清空当前驱动下的所有缓存
$cache->clear();
```
## (4). 驱动详细说明

YoPHP 采用策略模式设计，支持多驱动回退机制。

| 驱动名称 | 依赖环境 | 特点 | 适用场景 |
| :--- | :--- | :--- | :--- |
| **Redis** | `php-redis` 扩展 | 速度极快，支持分布式，数据持久化。 | 高并发、多服务器环境。 |
| **APCu** | `apcu` 扩展 | 内存级存储，无网络开销，单机性能王者。 | 单机高性能需求。 |
| **File** | `app/cache/` 目录可写 | 无需额外服务，通用性强，但磁盘 I/O 较慢。 | 基础环境、开发调试、最后的备用方案。 |

## (5). 进阶技巧：故障回退 (Failover) 展示
   YoPHP 内部的 __construct 实现逻辑如下，这在 2026 年依然是企业级框架的标准做法：
```php
try {
    // 尝试加载 Redis
    $this->_cache = $this->_loadDriver('redis');
} catch (Exception $e) {
    // Redis 报错，自动切换到 File 驱动
    $this->_cache = $this->_loadDriver('file');
}
```
* **开发者建议**：
  * 在生产环境中，建议将主驱动设为 redis 或 apcu，备份驱动设为 file。这样即便 Redis 服务器宕机，你的网站也不会因为缓存错误而崩溃，只会因为切换到文件缓存而响应稍慢。

## (6). 注意事项
* **文件权限**：使用 file 驱动时，请确保 app/cache/ 目录拥有读写权限。


* **序列化**：Redis 驱动默认开启了 SERIALIZER_PHP，这意味着你可以直接存取 PHP 数组而不需要手动 json_encode。


* **Key 安全性**：文件驱动内部使用 md5($id) 生成文件名，防止了非法字符导致的路径安全问题。

# 6. Validator 验证器组件使用说明
YoValidator 用于对用户提交的表单数据（$_POST）进行合法性校验。支持链式操作、自定义错误消息以及多种内置校验规则。

## (1). 核心特性
* **链式编程**：通过类似 $v->rule()->required()->run() 的语法，让逻辑一目了然。


* **多语言/模板支持**：支持 {label} 和 {param} 占位符，动态生成错误提示。


* **自动清洗**：验证过程中自动对字符串执行 trim 处理。


* **快速返回**：采用“断路器”模式，一旦发现第一个错误立即停止验证并返回，提升响应速度。


## (2). 基础用法
   在控制器中，你可以这样使用：
```php
public function save() {
    $v = new YoValidator();
    
    // 定义规则
    $v->rule('username', '用户名')->required()->alphaNumeric()->minLength(4);
    $v->rule('email', '电子邮箱')->required()->email();
    $v->rule('age', '年龄')->numeric();

    // 执行验证
    if ($v->run()) {
        // 验证通过，获取清洗后的数据
        $safeData = $v->getData();
        // 执行入库操作...
    } else {
        // 验证失败，获取第一条错误信息
        $error = $v->getErrorInfo();
        view()->json(['status' => 'error', 'msg' => $error]);
    }
}
```
## 3. 自定义错误消息
你可以为某个字段的特定规则单独定义错误信息：
```php
$v->rule('password', '密码')
  ->required()
  ->minLength(6)
  ->message([
      'required' => '亲，别忘了写{label}哦！',
      'minLen'   => '{label}太短了，至少要{param}位。'
  ]);
```
## (4). 内置验证规则参考表

| 方法 | 说明 | 示例 |
| :--- | :--- | :--- |
| **required()** | 必填项，不能为空 | `$v->rule('name', '姓名')->required()` |
| **email()** | 必须是合法的邮箱格式 | `$v->rule('mail', '邮箱')->email()` |
| **numeric()** | 必须是数字或数字字符串 | `$v->rule('price', '价格')->numeric()` |
| **minLength($n)** | 最小长度限制 | `->minLength(5)` |
| **maxLength($n)** | 最大长度限制 | `->maxLength(20)` |
| **alpha()** | 仅限字母 | `->alpha()` |
| **alphaNumeric()** | 仅限字母和数字 | `->alphaNumeric()` |
| **alphaDash()** | 字母、数字、下划线（及连字符） | `->alphaDash()` |
| **url()** | 必须是合法的 URL | `->url()` |
| **ip()** | 必须是合法的 IP 地址 | `->ip()` |

## (5). 错误提示模板说明
* 默认的错误模板定义在 $messages 属性中。你可以使用以下占位符：

* **{label}**：对应 rule() 方法的第二个参数（字段名称）。

* **{param}**：对应验证方法传入的参数（如 minLength(6) 中的 6）。


* 全局修改消息
  * 如果你想把整个框架的提示语改成英文或其它风格：
```php
$v->setMessages([
    'required' => '{label} is mandatory!',
    'email'    => 'Wrong format for {label}'
]);
```
## (6). 安全建议
* **后端必须校验**：虽然前端 HTML5 可以做基础验证，但永远不要信任前端传来的数据，必须通过 YoValidator 进行后端兜底。

* **配合数据获取**：验证通过后，请使用 $v->getData() 获取数据，而不是直接去拿 $_POST，因为 getData() 返回的是经过 trim 清洗过后的数据。


# 7. Upload 上传组件使用说明
YoUpload 是一个轻量级、高安全性的文件上传工具类。它支持按日期自动分目录存储、文件名加密以及严格的文件真伪校验。

## (1). 核心特性
* **深度防伪校验**：利用 PHP finfo 扩展检查文件二进制流，防止非法文件上传。


* **自动目录管理**：上传时自动按 Y/m/d 格式创建子目录，避免单个文件夹下文件过多导致性能下降。


* **文件名加密**：默认将文件名转换为唯一的 32 位 MD5 字符串，防止重名覆盖及中文乱码。


* **详细结果反馈**：上传成功后提供包括相对路径、绝对路径、文件大小等在内的全套信息。


## (2). 基础用法
* **A. 简单上传**
  * 在控制器中，你可以按照以下流程初始化并执行上传：
```php
$config = [
    'uploadPath'   => './uploads/images', // 物理根目录
    'allowedTypes' => 'jpg|png|gif',      // 允许的后缀
    'maxSize'      => 2048,               // 限制 2MB (单位 KB)
    'encryptName'  => true                // 重命名文件
];

$upload = new YoUpload($config);

if ($upload->doUpload('user_avatar')) {
    // 获取上传后的详细信息
    $fileInfo = $upload->getData();
    echo "上传成功！存储路径为：" . $fileInfo['relativePath'];
} else {
    // 获取错误提示
    echo "上传失败：" . $upload->getError();
}
```
* **B. getData() 返回值详解**
* 上传成功后，getData() 方法会返回一个关联数组，方便你直接存入数据库：

  | 键名 | 说明 | 示例值 |
  | :--- | :--- | :--- |
  | **fileName** | 存储后的文件名 | `a2b3c4...d5.jpg` |
  | **subPath** | 日期子目录 | `2026/02/19/` |
  | **relativePath** | **推荐存入数据库的路径** | `uploads/images/2026/02/19/xxx.jpg` |
  | **fileSize** | 文件大小 (KB) | `150.25` |
  | **fileMime** | 真实 MIME 类型 | `image/jpeg` |

## (3). 配置参数说明

| 配置项 | 类型 | 默认值 | 说明 |
| :--- | :--- | :--- | :--- |
| **uploadPath** | `string` | `''` | **必填**。设置文件存放的物理路径起点。 |
| **allowedTypes** | `string` | `'png\|jpg\|jpeg\|gif'` | 允许上传的后缀，使用 `|` 分隔。 |
| **maxSize** | `int` | `2048` | 最大上传限制，单位为 **KB**。 |
| **encryptName** | `bool` | `true` | 是否重命名文件。为 `false` 时保留原文件名（会进行安全清洗）。 |
| **fileNameSuffix** | `string` | `''` | 在加密文件名前增加固定前缀。 |

## (4). 安全机制解析
* **MIME 深度校验**
  * 传统的上传检查只看 $_FILES['file']['type']，这个值是由浏览器发送的，极易被伪造。
* **YoUpload 内部逻辑如下**：

  * 首先检查后缀名是否在 allowedTypes 列表中。

  * 核心步骤：使用 finfo 读取文件的真实二进制内容，并与后缀名映射表（isMimeAllowed）进行比对。

  * 如果内容是 PHP 脚本但伪造成 .jpg，校验将报错：“文件内容与后缀不匹配”。

* **目录创建**
  * 代码中使用 mkdir($fullPath, 0777, true)。第三个参数为 true 开启了递归模式，这意味着即使 uploads/ 目录存在，而 2026/02/19/ 不存在，它也会自动逐级创建完整路径。

## (5). 视图层示例 (HTML)
* 确保你的表单包含了 enctype="multipart/form-data" 属性：
```php
<form action="/user/upload" method="post" enctype="multipart/form-data">
    <input type="file" name="user_avatar" />
    <button type="submit">开始上传</button>
</form>
```
# 8. Image 图片组件使用说明
YoImage 专注于高效、高质量的图片处理。目前主要用于生成缩略图，并支持主流的 JPG、PNG 和 GIF 格式。

## (1). 核心特性
* **高质量重采样**：采用双三次插值算法进行缩放，避免锯齿和模糊。


* **智能比例缩放**：支持等比例缩放（maintainRatio），防止图片变形。


* **透明度保持**：完美支持 PNG 图片的透明通道处理。


* **内存管理**：自动销毁图像句柄，防止在处理大批量图片时耗尽服务器内存。


## (2). 基础用法
* **A. 生成缩略图**
* 你可以指定最大宽度和高度，系统会自动计算最佳比例。
```php
$img = new YoImage('./uploads/2026/02/19/original.jpg');

// 生成一个最大 200x200 的缩略图
// 默认会在同目录下生成 original_thumb.jpg
if ($img->thumb(200, 200, 'thumb')) {
    echo "缩略图生成成功！";
} else {
    echo "错误：" . $img->getError();
}
```
* **B. 覆盖原图**
* 如果你不需要保留原图，只需将 thumbName 设为空：
```php
$img->setSource('./uploads/temp.png');
$img->thumb(100, 100, '', 90); // 100x100, 质量90, 直接覆盖原文件
```
## (3). 方法参数详解
* thumb() 方法
* 这是该类的核心方法，参数定义如下：

| 参数 | 类型 | 默认值 | 说明 |
| :--- | :--- | :--- | :--- |
| **$width** | `int` | **必填** | 目标宽度的最大值。 |
| **$height** | `int` | **必填** | 目标高度的最大值。 |
| **$thumbName** | `string` | `'thumb'` | 后缀名。生成的图片名将变为 `文件名_thumb.扩展名`。若为空则覆盖原图。 |
| **$quality** | `int` | `100` | 图片质量 (1-100)。 |
| **$maintainRatio** | `bool` | `true` | 是否保持等比例。为 `false` 时会强行拉伸到指定尺寸。 |

## (4). 配合 YoUpload 使用
* 在实际开发中，通常在文件上传成功后紧接着生成缩略图：
```php
$upload = new YoUpload($config);
if ($upload->doUpload('avatar')) {
    $data = $upload->getData();
    
    // 实例化图像类
    $image = new YoImage($data['fullPath']);
    // 生成 150x150 的头像缩略图
    $image->thumb(150, 150, 'avatar_small');
}
```
## (5). 开发细节说明
* **关于 PNG 质量**
  * 在 output() 方法中，类库自动处理了 JPEG 和 PNG 的质量参数差异：
  * **JPEG**：接收 0-100 的质量数值。
  * **PNG**：自动将 0-100 的质量换算为 GD 库要求的 0-9 压缩级别（数值越大文件越小）。

* **透明度保护**
  * 处理 PNG 时，代码执行了以下关键操作，确保背景不会变成黑色：
```php
imagealphablending($dstImg, false); // 关闭混合模式
imagesavealpha($dstImg, true);      // 保存完整的 alpha 通道信息
```

# 9. Captcha 验证码组件使用指南
YoCaptcha 用于生成全自动的图形验证码，通过在图像中加入随机噪点和干扰线，能有效防止自动化脚本的恶意攻击。

## (1). 核心特性
* **无依赖运行**：使用 PHP GD 库内置字体，无需配置复杂的字体路径。

* **防混淆字符集**：自动剔除易错字符，提升用户体验。

* **安全性高**：内置随机噪点和不规则干扰线，增加 OCR 识别难度。

* **原生输出**：自动处理 header 头部，一行代码即可在前端显示。

## (2). 基础用法
* **A. 生成并显示验证码**
  * 在控制器（Controller）中创建一个方法来专门输出验证码图片：
```php
public function captcha() {
    // 初始化（宽, 高, 字符数）
    $captcha = new YoCaptcha(120, 40, 4);
    
    // 1. 获取生成的验证码内容（转为小写存入 Session，用于后续比对）
    $code = $captcha->getCode();
    $_SESSION['captcha_code'] = $code;
    
    // 2. 直接渲染并输出图片
    $captcha->doImage();
}
```
* **B. 校验验证码**
  * 在用户提交表单的逻辑中进行比对：
```php
public function doLogin() {
    $userInput = strtolower($this->post('captcha'));
    $savedCode = $_SESSION['captcha_code'];

    if ($userInput !== $savedCode) {
        die("验证码错误！");
    }
    // 继续后续逻辑...
}
```
## (3). 方法与参数说明
   __construct() 构造函数

| 参数 | 类型 | 默认值 | 说明 |
| :--- | :--- | :--- | :--- |
| **$width** | `int` | `120` | 验证码图片的宽度（单位：像素）。 |
| **$height** | `int` | `40` | 验证码图片的高度（单位：像素）。 |
| **$codeNum** | `int` | `4` | 验证码生成的字符数量。 |

* **核心方法**
  * **getCode()**: 返回当前生成的验证码字符串（小写），必须在 doImage() 之后或内部调用（因为它依赖于 createCode 的触发）。

  * **doImage()**: 执行绘图逻辑。包含设置背景、绘制噪点、绘制干扰线、填充文字以及发送 image/png 的 Header。

## (4). 样式细节
* **噪点设置**：默认生成 100 个随机颜色的小像素点。

* **干扰线**：生成 5 条随机位置的干扰线。

* **字符池**：23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ（已排除 0, 1, o, l, I 等）。

## (5). 前端调用示例
* 在 HTML 中，只需将 <img> 标签的 src 指向控制器的验证码方法：

```php
<div class="form-group">
    <input type="text" name="captcha" placeholder="请输入验证码">
    <img src="/auth/captcha" onclick="this.src='/auth/captcha?'+Math.random()" style="cursor:pointer;">
</div>
```

# 10. YoPHP框架的免责声明及使用建议
* 本项目不会对任何生产环境下的项目或产品的网络和软件安全问题负责, 如需在生产环境使用本框架, 请自行负责及解决所产生的一切问题和结果.


* YoPHP框架是基于清晰, 简单的方式进行构建, 如需要使用至生产环境产品, 请务必亲自阅读framework目录的全部代码, 使用示例在controllers目录ExampleController控制器中均有展示. 


* YoPHP框架性能接近原生PHP的运行速度, 日常测试QPS性能是其他主流重型PHP框架的10倍或以上, 环境需要PHP 7.0以上, 推荐PHP 8.0以后的版本使用, 性能更佳.


© 2026 ywlcjl. Licensed under the MIT License.
