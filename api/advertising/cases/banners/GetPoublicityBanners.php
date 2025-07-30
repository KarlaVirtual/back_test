<?php

    use Backend\dto\ConfigMandante;
    $partner = $_REQUEST['partner'];
    $country = $_REQUEST['country'];
    $language = $_REQUEST['language'];
    $type = $_REQUEST['type'];

    if(!in_array($type, [1, 2, 3])) die;

    $ConfigMandante = new ConfigMandante('', $partner);

    $data = json_decode($ConfigMandante->getConfig(), true);

    $banners = $data['bannersDesktop'][$country][$language]['login']['email'];

    if(oldCount($banners) === 0) die;

    $banners = array_filter($banners, function($data) {
        if(oldCount($data) > 0) return $data;
    });

    switch($type) {
        case 1:
            header('location:' . $banners[0]['redirect']);
            die;
        case 2:
            header('Content-Type: image');
            $img = file_get_contents($banners[0]['url']);
            die($img);
        case 3:
            header('Content-Type: text/HTML');
            break;
        default:
            die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <title>Banners</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #indicators {
            height: 15px;
            bottom: 0;
        }

        #indicators button {
            width: 15px;
            height: 15px;
            border-radius: 50%;
        }

        #indicators button:hover {
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators" id="indicators"></div>
        <div class="carousel-inner">
            <?php foreach($banners as $key => $value) { ?>
                <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                    <a href="<?= $value['redirect'] ?>">
                        <img class="d-block w-100" src="<?= $value['url'] ?>" alt="<?= $value['alt'] ?>">
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <script>
        const indicators = document.getElementById('indicators');
        const fragment = document.createDocumentFragment();

        const generateIndicators = (sliderTo) => {
            const button = document.createElement('button');
            button.setAttribute('type', 'button');
            button.setAttribute('data-bs-target', '#carouselExampleIndicators');
            button.setAttribute('data-bs-slide-to', sliderTo);
            button.setAttribute('class', 'indicator');
            if(sliderTo === 0) button.classList.add('active');
            fragment.appendChild(button);
            indicators.appendChild(fragment);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('.carousel-item');
            if(items.length  > 1) {
                items.forEach((_, key) => generateIndicators(key));
            }
        });
    </script>
</body>
</html>

<?php die; ?>