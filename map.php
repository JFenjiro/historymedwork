<?php 
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Region;
    use core\Model\Map;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    if (!isset($_SESSION["userid"])) {
        header("Location: ./login.php");
        exit;
    }

    include_once "./includes/header.php";
?>
    
    <main>

        <section class="quiz-0">
            
            <article class="search-menu-map">
                <form action="" method="get" class="select-map">

                    <fieldset>
        
                        <h2>Chercher son fond de carte</h2>
        
                        <div class="form2">
        
                            <select name="continent" id="continent">
                                <option value="continent" disabled selected>Continent</option>
                                <option value="afrique">Afrique</option>
                                <option value="ameriques">Amériques</option>
                                <option value="asie">Asie</option>
                                <option value="europe">Europe</option>
                                <option value="mediterranee">Méditerranée</option>
                            </select>
        
                        </div>
                        
                        <div class="form2">
        
                            <select name="region" id="region">
        
                                <option value="region" disabled selected>Région du Monde</option>
        
                                <optgroup label="Afrique">
                                    <option value="afrique-est">Afrique de l'Est</option>
                                    <option value="afrique-ouest">Afrique de l'Ouest</option>
                                    <option value="maghreb">Maghreb</option>
                                    <option value="nil">Vallée du Nil</option>
                                </optgroup>
        
                                <optgroup label="Amériques">
                                    <option value="amerique-nord">Amérique du Nord</option>
                                    <option value="amerique-sud">Amérique du Sud</option>
                                    <option value="yukatan">Golfe du Yukatan</option>
                                </optgroup>
        
                                <optgroup label="Asie">
                                    <option value="asie-centrale">Asie centrale</option>
                                    <option value="asie-sud-est">Asie du Sud-Est</option>
                                    <option value="monde-chine">Monde chinois</option>
                                    <option value="monde-inde">Monde indien</option>
                                    <option value="moyen-orient">Moyen-Orient</option>
                                    <option value="monde-arabe">Péninsule arabique</option>
                                </optgroup>
        
                                <optgroup label="Europe">
                                    <option value="europe-occident">Europe occidentale</option>
                                    <option value="europe-orient">Europe orientale</option>
                                    <option value="monde-russie">Monde russe</option>
                                    <option value="scandinavie">Scandinavie</option>
                                </optgroup>
        
                                <optgroup label="Méditerranée">
                                    <option value="mediterranee-occident">Méditerranée occidentale</option>
                                    <option value="mediterranee-orient">Méditerranée orientale</option>
                                    <option value="monde-byzance">Monde byzantin</option>
                                    <option value="proche-orient">Proche-Orient</option>
                                </optgroup>
        
                            </select>
        
                        </div>
                        
                        <div class="form2">
        
                            <input type="text" name="searchmap1" id="searchmap1" placeholder="Rechercher sa carte">
        
                        </div>
                        
                        <div class="form2">
        
                            <button type="button" class="searchmap2"><i class="fi fi-sr-check"></i></button>
        
                        </div>
        
                    </fieldset>               
        
                </form>

            </article>
            

            <article class="historic-maps">

                <div id="carte-fr">
                    <input type="image" src="img/Cartes/Europe_Moyen-Orient/France_Domaine_royal_987-1498(1).gif" alt="Carte domaine royal français" id="domaine-royal01">
                    <label for="domaine-royal01">Domaine royal français</label>
                </div>

                <div id="carte-caro">
                    <input type="image" src="img/Cartes/Europe_Moyen-Orient/Carolingiens(1).gif" alt="Carte empire carolingien" id="empire-carolingien01">
                    <label for="empire-carolingien01">Empire carolingien</label>
                </div>

                <div class="carte-just">
                    <input type="image" src="img/Cartes/Europe_Moyen-Orient/Justinien_limites(1).gif" alt="Carte empire de Justinien" id="empire-justinien01">
                    <label for="empire-justinien01">Empire byzantin de Justinien</label>
                </div>

                <div class="carte-omey">
                    <input type="image" src="img/Cartes/Europe_Moyen-Orient/Omeyyades(1).gif" alt="Carte empire omeyyade" id="empire-omeyyade01">
                    <label for="empire-omeyyade01">Empire omeyyade</label>
                </div>

                <div class="carte-ottom">
                    <input type="image" src="img/Cartes/Europe_Moyen-Orient/Ottomans(1).gif" alt="Carte empire ottoman" id="empire-ottoman01">
                    <label for="empire-ottoman01">Empire ottoman</label>
                </div>

                <div class="carte-occid">
                    <input type="image" src="img/Cartes/Europe_Moyen-Orient/Europe_Occident(1).gif" alt="Carte europe occident" id="europe-occident01">
                    <label for="europe-occident01">Europe occidentale</label>
                </div>

                <div class="carte-song">
                    <input type="image" src="img/Cartes/Chine/Song(1).gif" alt="Carte Chine Song" id="chine-song01">
                    <label for="chine-song01">Chine des Song</label>
                </div>

                <div class="carte-nihon">
                    <input type="image" src="img/Cartes/Asie/Japon(1).gif" alt="Carte Japon" id="empire-japon01">
                    <label for="empire-japon01">Empire du Soleil Levant</label>
                </div>

                <div class="carte-gupta">
                    <input type="image" src="img/Cartes/Inde/Gupta(1).gif" alt="Carte Inde Gupta" id="empire-gupta01">
                    <label for="empire-gupta01">Empire indien Gupta</label>
                </div>

                <div class="carte-inca">
                    <input type="image" src="img/Cartes/Amerique_Sud/Inca(1).gif" alt="Carte empire inca" id="empire-inca01">
                    <label for="empire-inca01">Empire inca</label>
                </div>

            </article>

        </section>

    </main>

<?php 
    include_once "./includes/footer.php";
?>
