<?

header('Content-Type: application/json; charset=utf-8');

echo json_encode(array(
	//"status" => "success",
	// или:
	"status" => "end_of_list",

	"items" => array(
		array(
			"id" => "bx_id_10",
			"preview" => array(
				"description" => "some alt text",
				"src" => "/upload/markup_tmp/collection/01.png",
				//"width" => 123, // [optional]
				//"height" => 123, // [optional]
			),
			//"info" => [optional]
		),
		array(
			"id" => "bx_id_11",
			"preview" => array(
				"description" => "some another alt text",
				"src" => "/upload/markup_tmp/collection/02.png",
				"width" => 500, // [optional]
				"height" => 500, // [optional]
			),
			"info" => array(
				"text" => <<<HTML
				<p>Арт. ТТ3932</p>
				<p>
					Белое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Желтое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Вес: 3,02 г.<br/>
					Размер: 15,5 мм.
				</p>
				<p>
					ТРЦ «Золотой Вавилон»<br/>
					Тел.: (863) 204-07-40
				</p>
HTML
				,
				"picture" => array(
					/*поидее можно тупо клонировать ключ "preview"
					если обратного не требует задача*/
					"description" => "some another alt text",
					"src" => "/upload/markup_tmp/collection/02.png",
					"width" => 500, // [optional]
					"height" => 500, // [optional]
				),
			),
		),
	),
));
