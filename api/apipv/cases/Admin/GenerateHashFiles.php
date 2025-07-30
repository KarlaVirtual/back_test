<?php

/* Genera un archivo SHA-1 si no existe, basado en archivos del directorio cert. */
header('Content-Type: text/html');

if ($_POST["time"] != '') {
    if (!file_exists('/home/devadmin/api' . "/log-sha/" . $_POST["time"] . ".sha1")) {
        exec("cd /home/devadmin/ && find ./cert -type f -print0 | xargs -0 -n1 -i sha1sum {} > ./api/log-sha/" . $_POST["time"] . ".sha1");
    }
}


?>


<style>
    @charset "UTF-8";
    body {
        font-family: 'Open Sans', sans-serif;
        background: #efefef;
        text-transform: uppercase;
    }

    h1 {
        margin-top: 50px;
        margin-bottom: 15px;
        margin-right: 12.5%;
        text-align: right;
        font-size: 1.5em;
        color: #bbb;
        letter-spacing: 0.1em;
    }

    td, a {
        transition: color 333ms ease-in-out;
    }

    a {
        color: #407DB8;
        text-decoration: none;
        font-weight: 400;
    }

    .table {
        width: 75%;
        margin: 0 12.5% 50px 12.5%;
    }

    tr {
        height: 40px;
        transition: background 333ms ease-in-out;
    }

/* Establece colores de fondo alternos y efectos al pasar el cursor sobre filas de tabla. */
    tr:nth-child(even) {
        background: #e6f0f6;
    }

    tr:nth-child(odd) {
        background: #fff;
    }

    tr:hover {
        background: #ddd;
    }

    tr:hover:nth-child(even) {
        background: #ddd;
    }

    tr:hover td {
        color: #888;
    }

    tr:hover a {
        color: #666;
    }

    tr td {
        color: #888;
        font-size: 0.78em;
        font-weight: 300;
        letter-spacing: 0.2em;
    }

    tr td:first-child {
        padding-left: 25px;
    }

    tr td:last-child {
        padding-right: 25px;
        text-align: right;
    }

    /* Icons */
    .folder:before, .file:before {
        margin-right: 7px;
        font-size: 0.7em;
    }

    .folder:before {
        content: '▶';
        opacity: 0.6;
    }

    .file:before {
        content: '▶';
        opacity: 0.2;
    }

    /* Top Bar */
    tr:first-child {
        background: #666;
    }

    tr:first-child td {
        color: #eee;
        font-size: 0.75em;
        letter-spacing: 0.3em;
    }

    tr:first-child,
    tr:first-child td {
        transition: none;
    }

    /* Download Bar */
    .download {
        width: 100%;
        background: #437EB6;
        text-align: center;
        padding: 8px 0;
    }

    .download a, .download button {
        color: white;
        font-weight: 700;
    }

    .download a:after, .download button:after {
        content: "▶";
        margin-left: 7px;
        font-size: 0.8em;
        opacity: 0.7;
    }

    .download a:hover, .download button:hover {
        color: #163f65;
    }
</style>
<body>
<style>* {
        padding: 0;
        margin: 0;
    }</style>

<div class="download">
    <form action="" method="post" id="formHash">
        <input type="text" name="time" value="<?= time(); ?>" hidden/>
        <p>
            <a href="#" onclick="document.getElementById('formHash').submit()">Generate Hash NOW !</a>

        </p>
    </form>
</div>

<h1>HASH Directory</h1>
<table cellspacing="0" class="table">
    <tbody>
    <tr>
        <td class="head">Date</td>
        <td class="head">Type</td>
        <td class="head">Size</td>
    </tr>

    <?php

/* lista archivos de un directorio y muestra sus detalles en una tabla HTML. */
    $path = __DIR__ . "/../../../../log-sha/";
    $files = scandir($path);
    foreach ($files as &$value) {
        if ($value != '.' && $value != '..' && date('Y-m-d H:i:s', $value) != '') {
            echo "<tr>
        <td><a href='https://devadmin.doradobet.com/api/log-sha/" . $value . "' target='_blank' >" . date('Y-m-d H:i:s', $value) . "</a></td>
        <td> Hash </td>
        <td>" . round(filesize($path . $value) / 1000000, 2) . " MB</td>
    </tr>";
        }
    }

    ?>


    </tbody>
</table>

</body>
