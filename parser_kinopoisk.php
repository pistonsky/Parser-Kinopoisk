<?php
	header('Content-Type: text/html; charset=windows-1251');

	include "Snoopy.class.php";
	$snoopy = new Snoopy;
	$cache = true;
	
	if ( !isset($cache) || !$cache ){
		$snoopy->maxredirs = 2;
		
		//авторизация, чтобы не банили
		
		$post_array = array(
			'shop_user[login]' => 'dimmduh',
			'shop_user[pass]' => 'gfhjkm03',
			'shop_user[mem]' => 'on',
			'auth' => 'go',
		);
		
		$snoopy -> agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; uk; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 Some plugins";
		
		//отправляем данные для авторизации
		$snoopy->submit('http://www.kinopoisk.ru/level/30/', $post_array);
		//print $snoopy -> results;
		
		//забираем трансформеров
		$snoopy -> fetch('http://www.kinopoisk.ru/level/1/film/452899/');
		$result = $snoopy -> results;
		file_put_contents('temp', $result );
	} else {
		$result = file_get_contents('temp');
	}
	
	$parse = array(
		'name' =>         '#<h1 style=\"margin: 0; padding: 0\" class="moviename-big">(.*?)</h1>#si',
		'originalname'=>  '#13px">(.*?)</span>#si',
		'year' =>         '#<a href="/level/10/m_act%5Byear%5D/([0-9]+)/" title="">#si',
		'country_title' =>'#страна.*?<a href="/level/10/m_act%5Bcountry%5D/[0-9]+/">(.*?)</a>#si',
		'country_id' =>   '#страна.*?<a href="/level/10/m_act%5Bcountry%5D/([0-9]+)/">.*?</a>#si',
		'slogan' =>       '#слоган</td><td style="color: \#555">(.*?)</td></tr>#si',
		'director' =>     '#режиссер</td><td>(.*?)</td></tr>#si',
		'script' =>       '#сценарий</td><td>(.*?)</td></tr>#si',
		'producer' =>     '#продюсер</td><td>(.*?)</td></tr>#si',
		'operator' =>     '#оператор</td><td>(.*?)</td></tr>#si',
		'composer' =>     '#композитор</td><td>(.*?)</td></tr>#si',
		'genre' =>        '#жанр</td><td>(.*?)</td></tr>#si',
		'budget' =>       '#бюджет</td>.*?<a href="/level/85/film/[0-9]+/" title="">(.*?)</a>#si',
		'usa_charges' =>  '#сборы в США</td>.*?<a href="/level/85/film/[0-9]+/" title="">(.*?)</a>#si',
		'world_charges' =>'#сборы в мире</td>.*?<a href="/level/85/film/[0-9]+/" title="">(.*?)</a>#si',
		'rus_charges' =>  '#сборы в России</td>.*?<div style="position: relative">(.*?)</div>#si',
		'world_premiere'=>'#премьера \(мир\)</td>.*?<a href="/level/80/film/[0-9]+/" title="">(.*?)</a>#si',
		'rus_premiere' => '#премьера \(РФ\)</td>.*?<a href="/level/8/view/prem/year/[0-9]+/\#[0-9]+">(.*?)</a>#si',
		//'dvd' =>          '#dvd">(.*?)</td></tr>#is',
		//'bluray' =>       '#bluray">(.*?)</td></tr>#is',
		//'MPAA' =>         '#MPAA</td><td class=\"[\S]{1,100}\"><a href=\'[\S]{1,100}\'><img src=\'/[\S]{1,100}\' height=11 alt=\'(.*?)\' border=0#si',
		'time' =>         '#id="runtime">(.*?)</td></tr>#si',
		'description' =>  '#<span class=\"_reachbanner_\">(.*?)</span>#si',
		'imdb' =>         '#IMDB:\s(.*?)</div>#si',
		'kinopoisk' =>    '#text-decoration: none">(.*?)<span#si',
		'kp_votes' =>     '#<span style=\"font:100 14px tahoma, verdana\">(.*?)</span>#si',
	);
 
 
   $new=array();
   foreach($parse as $index => $value){
		preg_match($value,$result,$matches);
		$new[$index] = preg_replace("#<a.+?>(.+?)</a>#is","$1",$matches[1]);
   }
   print_r( $new );
	
?>