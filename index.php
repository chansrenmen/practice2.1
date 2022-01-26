<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>
<body>
	<div class="text d-none">
		<h1 class="d-none">Lorem Ipsum</h1>
		<h3>"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean faucibus felis at lorem suscipit elementum sed vel sapien. 
			Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent non purus sit amet sapien feugiat pulvinar a et lectus. </p>
		<p>Mauris in erat non purus scelerisque porttitor vel vel risus. Nulla et orci ut sem pharetra hendrerit ornare a elit. 
			Fusce turpis nunc, commodo vitae dapibus quis, tristique viverra dolor. Etiam ut fermentum orci.</p>
	</div>
	
  <div style="max-width: 500px; margin-left: auto; margin-right: auto; margin-top: 20px;">

    <!-- Сообщение которое будем показывать при успешной отправки формы -->
    <div class="form-result d-none">Форма успешно отправлена!</div>
    <!-- Форма -->
    <form id="form" action="assets/php/process-form.php" method="post">
      <!-- Капча -->
      <div class="captcha">
        <div class="captcha__image-reload">
          <img class="captcha__image" src="assets/php/captcha.php" width="132" alt="captcha">
          <button type="button" class="captcha__refresh"></button>
        </div>
        <div class="captcha__group">
          <label for="captcha">Код, изображенный на картинке</label>
          <input type="text" name="captcha" id="captcha">
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <!-- Кнопка "Отправить" -->
      <button type="submit">Отправить</button>
    </form>

  </div>

  <script>
    const refreshCaptcha = (target) => {
      const captchaImage = target.closest('.captcha__image-reload').querySelector('.captcha__image');
      captchaImage.src = 'assets/php/captcha.php?r=' + new Date().getUTCMilliseconds();
    }

    const captchaBtn = document.querySelector('.captcha__refresh');
    captchaBtn.addEventListener('click', (e) => refreshCaptcha(e.target));

    const form = document.querySelector('#form');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      try {
        fetch(form.action, {
          method: form.method,
          credentials: 'same-origin',
          body: new FormData(form)
        })
          .then((response) => {
            return response.json();
          })
          .then((data) => {
            document.querySelectorAll('input.is-invalid').forEach((input) => {
              input.classList.remove('is-invalid');
              input.nextElementSibling.textContent = '';
            });
            if (!data.success) {
              refreshCaptcha(form.querySelector('.captcha__refresh'));
              data.errors.forEach(error => {
                const input = form.querySelector(`[name="${error[0]}"]`);
                if (input) {
                  input.classList.add('is-invalid');
                  input.nextElementSibling.textContent = error[1];
                }
              })
            } else {
              form.reset();
              form.querySelector('.captcha__refresh').disabled = true;
              form.querySelector('[type=submit]').disabled = true;
			  // document.location.href='new.php'; // Ссылка на другую страницу с текстом.
			  document.getElementById('form').style.display='none';
			  document.querySelector('.text').classList.remove('d-none');
            }
          });
      } catch (error) {
        console.error('Ошибка:', error);
      }
    });
  </script>

</body>

</html>
