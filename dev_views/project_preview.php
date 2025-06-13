<!DOCTYPE html><!DOCTYPE html><!DOCTYPE html><!DOCTYPE html>
















































































</html></body></script>    });        });            $('#preview-iframe').css({ width: width + 'px', height: height + 'px' });            $('#frame-container').css({ width: width + 'px', height: height + 'px' });            }                height = 800;                width = 1280;            } else {                height = 1024;                width = 768;            } else if (device === 'tablet') {                height = 667;                width = 375;            if (device === 'mobile') {            var width, height;            var device = $(this).val();        $('#device-select').change(function() {    $(document).ready(function() {<script></div>    </div>        <div class="device-home"></div>        <iframe id="preview-iframe" src="<?= htmlspecialchars($preview_url) ?>" style="width:100%;height:100%;border:0;border-radius:16px;background:#fff;"></iframe>        <div class="device-speaker"></div>    <div id="frame-container" class="device-frame" style="width:1280px;height:800px;">    </div>        <a href="?projects=1" class="btn btn-secondary ms-3"><?= t('back_to_projects') ?></a>        </select>            <option value="desktop" selected><?= t('desktop_device') ?> (1280x800)</option>            <option value="tablet"><?= t('tablet_device') ?> (768x1024)</option>            <option value="mobile"><?= t('mobile_device') ?> (375x667)</option>        <select id="device-select" class="form-select" style="max-width:300px;display:inline-block;">        <label for="device-select" class="device-label"><?= t('device') ?>:</label>    <div class="mb-3">    <h1 class="mb-3"><?= t('project_preview') ?></h1><div class="container mt-4"><body></head>    </style>        }            border-radius: 2px;            background: #666;            height: 4px;            width: 40px;            transform: translateX(-50%);            left: 50%;            bottom: 8px;            position: absolute;        .device-home {        }            border-radius: 4px;            background: #666;            height: 8px;            width: 60%;            transform: translateX(-50%);            left: 50%;            top: 8px;            position: absolute;        .device-speaker {        }            background: #000;            overflow: hidden;            border-radius: 16px;            border: 16px solid #333;            position: relative;        .device-frame {    <style>    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">    <title><?= t('project_preview') ?> — DevManager</title>    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta charset="UTF-8"><head><html lang="ru">




























































</html></body>    </div>        <div class="device-home"></div>        <iframe id="preview-iframe" src="<?= htmlspecialchars($preview_url) ?>"></iframe>        <div class="device-speaker"></div>    <div class="device-frame"><body></head>    </style>        }            background: #fff;            border-radius: 16px;            border: 0;            height: 100%;            width: 100%;        iframe {        }            border-radius: 50%;            border: 2px solid #555;            background: #222;            height: 36px;            width: 36px;            transform: translateX(-50%);            left: 50%;            bottom: 10px;            position: absolute;        .device-home {        }            border-radius: 3px;            background: #444;            height: 6px;            width: 60px;            transform: translateX(-50%);            left: 50%;            top: 12px;            position: absolute;        .device-speaker {        }            overflow: hidden;            background: #000;            border-radius: 36px;            border: 16px solid #333;            height: 667px;            width: 375px;            position: relative;        .device-frame {        }            background-color: #f0f0f0;            margin: 0;            height: 100vh;            align-items: center;            justify-content: center;            display: flex;        body {    <style>    <title>Предпросмотр проекта</title>    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta charset="UTF-8"><head><html lang="ru">



















































</html></body>    <!-- ...existing JS for device emulation... -->    </script>        })();            setTheme(theme);            const theme = localStorage.getItem('dev_theme') || 'light';        (function() {        };            setTheme(theme);            const theme = document.body.classList.contains('dark-theme') ? 'light' : 'dark';        document.getElementById('theme-toggle-btn').onclick = function() {        }            localStorage.setItem('dev_theme', theme);            }                document.getElementById('theme-toggle-btn').innerText = '🌙';                document.body.classList.remove('dark-theme');            } else {                document.getElementById('theme-toggle-btn').innerText = '☀️';                document.body.classList.add('dark-theme');            if (theme === 'dark') {        function setTheme(theme) {    <script>    </div>        <!-- ...existing code... -->    <div class="container mt-4">    </button>        🌙    <button class="btn btn-outline-secondary theme-toggle" id="theme-toggle-btn" title="Переключить тему"><body></head>    </style>        /* ...existing device-frame styles... */        }            right: 1rem;            top: 1rem;            position: absolute;        .theme-toggle {        }            border-color: #444 !important;            color: #e8eaed !important;            background-color: #23272b !important;        .dark-theme .card, .dark-theme .list-group-item, .dark-theme .form-control, .dark-theme .btn, .dark-theme .modal-content {        }            color: #e8eaed !important;            background: #181a1b !important;        body.dark-theme {    <style>    <link id="theme-link" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">    <!-- ...existing code... --><head><html lang="ru"><html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Предпросмотр проекта — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .device-frame {
            margin: 0 auto;
            border: 8px solid #333;
            border-radius: 24px;
            background: #222;
            box-shadow: 0 0 24px #0004;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: width .3s, height .3s;
        }
        .device-label {
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-3">Предпросмотр проекта</h1>
    <div class="mb-3">
        <label for="device-select" class="device-label">Устройство:</label>
        <select id="device-select" class="form-select" style="max-width:300px;display:inline-block;">
            <option value="mobile">Мобильный (375x667)</option>
            <option value="tablet">Планшет (768x1024)</option>
            <option value="desktop" selected>Десктоп (1280x800)</option>
        </select>
        <a href="?projects=1" class="btn btn-secondary ms-3">Назад к проектам</a>
    </div>
    <div id="frame-container" class="device-frame" style="width:1280px;height:800px;">
        <iframe id="preview-iframe" src="<?= htmlspecialchars($preview_url) ?>" style="width:100%;height:100%;border:0;border-radius:16px;background:#fff;"></iframe>
    </div>
</div>
<script>
    const deviceSizes = {
        mobile:  {w: 375, h: 667},
        tablet:  {w: 768, h: 1024},
        desktop: {w: 1280, h: 800}
    };
    const select = document.getElementById('device-select');
    const frame = document.getElementById('frame-container');
    select.addEventListener('change', function() {
        const d = deviceSizes[this.value];
        frame.style.width = d.w + 'px';
        frame.style.height = d.h + 'px';
    });
    // Установить размер по параметру device (если есть)
    (function() {
        const params = new URLSearchParams(window.location.search);
        const device = params.get('device') || 'desktop';
        if (deviceSizes[device]) {
            select.value = device;
            const d = deviceSizes[device];
            frame.style.width = d.w + 'px';
            frame.style.height = d.h + 'px';
        }
    })();
</script>
</body>
</html>
