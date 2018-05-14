<?php

class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null,
			$_clans_names = array("Uchiha", "Senju", "Nara", "Kiyoshi", "Ayatsuri", "Namikaze", "Sabaku", "Hyuga", "Yuki", "Inuzuka", "Kaguya", "Akimichi", "Aburame", "Futago", "Kuran Sen", "Kakukuro"),
			$_charakters_names = array("Naruto", "Sasuke", "Hinata", "Kakashi", "Sakura", "Neji", "Sai", "Kabuto", "Madara", "Shikaramu", "Gai", "Rock Lee", "Konohamaru", "Kyuubi", "Boruto", "Obito", "Sarada", "Itachi", "Nagato", "Hinabi", "Orochimaru", "Minato", "Hagoromo", "Gaara", "Kurama", "Tsunade", "Hashirama", "Jiraiya", "Kushina", "Hiruzen", "Shisui", "Deirada", "Kisame", "Danzo", "Temari", "Ino", "Rin", "Mito", "Tobirama", "Sasori", "Kakuzu", "Hidan", "Asuma", "Karin", "Konan", "Hiashi", "Yamato", "Tenten", "Kiba", "Zabuza", "Zetsu", "Anko", "Yashiko", "Choji", "Kurenai", "Kimimaro", "Shino", "Shukaku", "Karui", "Mei", "Nawaki", "Kankuro", "Suigetsu", "Hanzo", "Haku", "Matatabi", "Darui", "Shizune", "Jugo", "Ao", "Yagura", "Guren", "Izuna", "Utakata", "Iruka", "Shikaku", "Yugito", "Kurotsuchi", "Chojuro", "Gamabunta", "Tobi", "Inoichi", "Raiga", "Onoki", "Hizashi", "Dan", "Chiyo", "Ibiki", "Omoi", "Gamamaru", "Kinkaku", "Manda", "Torune", "Mifune", "Gengetsu", "Yukimaru", "Hayate", "Sakon", "Ukon", "Akamaru", "Ebisu", "Yashamaru", "Gamakichi", "Ranmaru", "Moegi", "Katsuyu", "Ginkaku", "Udon", "Baki", "Tazuna", "Enma", "Fukasaku", "Kidomaru"),
			$_secret = "6Ldy3QsUAAAAAKGsKjFXHJIot8KGP-nKO6PaJC72";
			// $_secret = "6LcXnyYUAAAAAG56b_kDF11QA_DN7ccoPp3mVFqy";


	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()) {
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				if($rule == 'name') {
					$name = $rule_value;
				}
				else if($rule == 'required' && empty($source[$item])) {
					$this->addError("Pole ".$name.' jest wymagane!');
				}
				else if($rule == 'fileRequired' && empty($source[$item]['tmp_name'])) {
					$this->addError('Wymagany plik '.$name.' nie został wybrany!');
				}
				else if($rule == 'requiredCaptcha' && empty($source[$item])) {
					$this->addError("Potwierdź, że nie jesteś botem.");
				}
				else if($rule == 'checked' && !isset($source[$item])) {
					$this->addError("Należy zatwierdzić ".$name.'!');
				}
				else if(!empty($source[$item])) {
					switch($rule) {
						case 'min_char':
							if(strlen($source[$item]) < $rule_value) {
								$this->addError(ucfirst($name." musi składać się przynajmniej z ".$rule_value." znaków"));
							}
							break;

						case 'max_char':
							if(strlen($source[$item]) > $rule_value) {
								$this->addError(ucfirst($name." może składać się maksymalnie z ".$rule_value." znaków"));
							}
							break;

						case 'min_size':
							if($source[$item] < $rule_value) {
								$this->addError(ucfirst($name." nie może być mniejsza niż ".$rule_value));
							}
							break;

						case 'max_size':
							if($source[$item] > $rule_value) {
								$this->addError(ucfirst($name." nie może być większa niż ".$rule_value));
							}
							break;

						case 'letter_only':
							if(preg_match('/^[a-zA-z\s]+$/D', $source[$item]) == false) {
								$this->addError(ucfirst($name." może składać się tylko z liter (bez polskich znaków oraz cyfr)!"));
							}
							break;

						case 'letter_and_polish_only':
							if(preg_match('/^[a-z|A-z|ą|ę|ś|ó|ź|ż|ć|ń|ł|Ą|Ę|Ś|Ó|Ź|Ż|Ć|Ń|Ł|\s]+$/D', $source[$item]) == false) {
								$this->addError(ucfirst($name." może składać się tylko z liter (dozwolona jest spacja oraz polskie znaki diakrytyczne, bez cyfr i znaków specjalnych)!"));
							}
							break;

						case 'letter_and_japanise_only':
							if(preg_match('/^[a-zA-zōūāīŌŪĀĪ\s]+$/D', $source[$item]) == false) {
								$this->addError(ucfirst($name." może składać się tylko z liter (dozwolona jest spacja oraz Rōmaji, bez cyfr i znaków specjalnych)!"));
							}
							break;

						case 'no_clan_names':
							foreach ($this->_clans_names as $clan_id => $clan_name) {
								$pattern = '/'.$clan_name.'/i';
								if(preg_match($pattern, $source[$item])) {
									$this->addError(ucfirst($name." nie może zawierać nazw klanów!"));
									break;
								}
							}
							break;

						case 'no_anime_names':
							foreach ($this->_charakters_names as $charakter_id => $charakter_name) {
								$pattern = '/'.$charakter_name.'/i';
								if(preg_match($pattern, $source[$item])) {
									$this->addError(ucfirst($name." nie może zawierać imion postaci kanonicznych!"));
									break;
								}
							}
							break;

						case 'matches':
							if($source[$item] != $source[$rule_value]) {
								$this->addError(ucfirst($name." oraz ".$source[$rule_value]." musz być takie same."));
							}
							break;

						case 'email_validate':
							$email_validate = filter_var($source[$item], FILTER_SANITIZE_EMAIL);
							if((filter_var($email_validate, FILTER_VALIDATE_EMAIL) == false) || ($email_validate != $source[$item])) {
								$this->addError("Niepoprawny email.");
							}
							break;

						case 'ip_validate':
							if(!filter_var($source[$item], FILTER_VALIDATE_IP)) {
								$this->addError("Niepoprawny adres IP.");
							}
							break;

						case 'unique':
							$check = $this->_db->get($rule_value[0], array($rule_value[1], '=', $source[$item]))->count();
							if($check != 0) {
								$this->addError(ucfirst($name." już istnieje w naszej bazie."));
							}
							break;

						case 'must_exists':
							$check = $this->_db->get($rule_value[0], array($rule_value[1], '=', $source[$item]))->count();
							if($check == 0) {
								$this->addError(ucfirst($name." nie istnieje w naszej bazie."));
							}
							break;

						case 'recaptcha':
							$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->_secret.'&response='.$source[$item]);
							$response = json_decode($check);
							if($response->success == false) {
								$this->addError("Potwierdź, że nie jesteś botem.");
							}
							break;

						case 'avatar_file':
							$avatar_types = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
							if(!empty($source[$item]['tmp_name'])) {
								if(getimagesize($source[$item]['tmp_name'])) {
									if(in_array($source[$item]['type'], $avatar_types)) {
										if($source[$item]['size'] < 500000) {
											list($avatar_width, $avatar_height) = getimagesize($source[$item]['tmp_name']);
											if($avatar_width == 185) {
												if($avatar_height != 260) {
													$this->addError("Jedyna obsługiwana wysokość avatara to 260 pikseli.");
												}
											} else {
												$this->addError("Jedyna obsługiwana szerokość avatara to 185 pikseli.");
											}
										} else {
											$this->addError("Rozmiar avatara nie może być większy niż 500KB.");
										}
									} else {
										$this->addError("Niedozwolony format! Dozwolone formaty plików to png, jpeg, jpg oraz gif.");
									}
								} else {
									$this->addError("Wybrano plik niebędący grafiką.");
								}
							} else {
								$this->addError("Żaden plik nie został wybrany.");
							}
							break;

						case 'img_verify':
							if(!empty($source[$item]['tmp_name'])) {
								if(!getimagesize($source[$item]['tmp_name'])) {
									$this->addError("Wybrano plik niebędący grafiką.");
								}
								break;
							}

						case 'img_types':
							if(!empty($source[$item]['tmp_name'])) {
								$avatar_types = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
								if(!in_array($source[$item]['type'], $avatar_types)) {
									$this->addError("Niedozwolony format! Dozwolone formaty plików to png, jpeg, jpg oraz gif.");
								}
								break;
							}

						case 'img_width':
							if(!empty($source[$item]['tmp_name'])) {
								list($avatar_width, $avatar_height) = getimagesize($source[$item]['tmp_name']);
								if($avatar_width != $rule_value) {
									$this->addError("Szerokość podanej grafiki powinna wynosić ".$rule_value."px");
								}
								break;
							}

						case 'img_height':
							if(!empty($source[$item]['tmp_name'])) {
								list($avatar_width, $avatar_height) = getimagesize($source[$item]['tmp_name']);
								if($avatar_height != $rule_value) {
									$this->addError("Wysokość podanej grafiki powinna wynosić ".$rule_value."px");
								}
								break;
							}
					}
				}
			}
		}

		if(empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}