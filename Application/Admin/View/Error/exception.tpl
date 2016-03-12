<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>500错误</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <script src="/Public/Admin/js/jquery-1.10.2.js"></script>
    <!-- 基本的样式 -->
    <link href="/Public/Admin/css/bootstrap.min.css?v=3.3.0" rel="stylesheet">
    <link href="/Public/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/css/style.css?v=2.1.0" rel="stylesheet">
    <!--图标样式-->
    <link href="/Public/Admin/font/css/font-awesome.css?v=4.3.0" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="row wrapper wrapper-content animated fadeinup">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                            <h1><i class="fa fa-bug"></i>服务器异常</h1>
                        </div>
                        <dl class="dl-horizontal">
                            <dt>异常文件名</dt>
                            <dd><?php echo $e['file']; ?></dd>
                        </dl>
                        <dl class="dl-horizontal">
                            <dt>异常发生的文件行数</dt>
                            <dd><?php echo $e['line']; ?></dd>
                        </dl>
                        <dl class="dl-horizontal">
                            <dt>错误信息</dt>
                            <dd><?php echo $e['message']; ?></dd>
                        </dl>
                        <dl class="dl-horizontal">
                            <dt>错误堆栈</dt>
                            <dd><?php echo nl2br($e['trace']); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>