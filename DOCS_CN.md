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

🛠️ 快速上手
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

🛡️ 安全建议
确保 public/ 目录为 Web 服务器的 Document Root。

生产环境下将 config.php 中的 DEBUG_MODE 设置为 product。

© 2026 ywlcjl. Licensed under the MIT License.
