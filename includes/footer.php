    <!-- footer avec menu secondaire -->
        <footer>

            <div id="footer-flex">
                <!-- Mentions légales -->
                <div class="mentions">
                    <h2>Mentions légales</h2>
                    <div id="about-me">
                        <ul>
                            <li>Développeur : Jounayd Fenjirou</li>
                            <li>
                                Contact :
                                <ul>
                                    <li>Email : jfenjirou@gmail.com</li>
                                    <li>LinkedIn : <a href="https://www.linkedin.com/in/jounayd-fenjirou/" target="_blank">https://www.linkedin.com/in/jounayd-fenjirou/</a></li>
                                    <li>Portfolio : <a href="https://www.portfolio.historymedwork.com/" target="_blank">https://www.portfolio.historymedwork.com/</a></li>
                                </ul>
                            </li>
                            <li>Développeur junior</li>
                        </ul>
                    </div>
                </div>

                <!-- Sources -->
                <div id="ref-other">
                    <h2>Ressources</h2>
                    <ul>
                        <li>
                            Ressources externes
                            <ul>
                                <li>Icônes du site : <a href="https://www.flaticon.com/authors/freepik" target="_blank">Freepik sur Flaticon</a></li>
                                <li>Traitement des images : <a href="https://www.canva.com/" target="_blank">Canva</a></li>
                                <li>Fonds de cartes : <a href="https://d-maps.com/index.php" target="_blank">D-Maps.com</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            
            <span>&copy;2023 - Jounayd Fenjirou</span>
        </footer>
        
        <!-- Liens JavaScript -->
        <script src="./assets/js/app.js"></script>
        <script src="./assets/js/quiz.js"></script>
        <script src="./assets/js/canvas.js"></script>
        <script src="./assets/js/workspace.js"></script>
        <script>tinymce.init({selector: 'textarea#redactor', plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker', toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat', tinycomments_mode: 'embedded', tinycomments_author: 'Author name', mergetags_list: [{ value: 'First.Name', title: 'First Name' },{ value: 'Email', title: 'Email' },],});</script>

        <!-- Liens vers les confettis -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha256-9SEPo+fwJFpMUet/KACSwO+Z/dKMReF9q4zFhU/fT9M=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js" integrity="sha256-qXBd/EfAdjOA2FGrGAG+b3YBn2tn5A6bhz+LSgYD96k=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/highlight.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
        <?php if (isset($_POST["quiz-id"])): ?>
            <script src="./assets/js/confettis.js"></script> 
        <?php endif; ?>
        
    </body>

</html>