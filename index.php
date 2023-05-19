<?php
require('config.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" 
    crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <nav class="px-3 py-2 border-bottom mb-3">
        <div class="container d-flex flex-wrap justify-content-center">
            <div class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto">
                <h1>
                    Test task : 1.2
                </h1>
            </div>
            <div class="text-end">

                <button id = 'signin_button' class="btn btn-light text-dark me-2" onclick = 'toogle_containers(login_form_container)'>login</button>
                <button id = 'signup_button' class="btn btn-primary" onclick = 'toogle_containers(signup_form_container)'>register</button>
                <button id = 'signout_button' class="btn btn-light text-dark me-2" onclick = 'logout(event)'>logout</button>
            </div>
        </div>
        <div id = 'errors_dashboard'>

        </div>
    </nav>
    <script>
        function toogle_containers(must_on){
            login_form_container.style.display = 'none';
            signup_form_container.style.display = 'none';
            user_info_container.style.display = 'none';
            console.log(must_on);
            must_on.style = '';
        }

        function logout(e){
            e.preventDefault();
            rqst = new XMLHttpRequest();
            rqst.open('POST', 'signout.php');
            rqst.onload = function(){
                console.log(rqst.response);
                draw_page(prepare_page_data(rqst.response));
            };

            rqst.send();
        }
    </script>
    <div class='w-100'>
        <div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" >
            <div class="modal-dialog" id="login_form_container">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h4 class="fw-bold mb-0 fs-2">
                        signin
                    </h4>
                </div>
                <div class="modal-body p-5 pt-0">
                    <div class="login_form" >
                        <form action="login" method="post" id=signin_form>
                            <div class="form-floating mb-3">
                                <input type="text" name="name" id="name"  class="form-control rounded-3">
                                <label for="name">name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="password" id="pass"  class="form-control rounded-3">
                                <label for="pass">password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" onclick="login(event)">login</button>
                            </div>
                        </form>
                        <script>
                            function login(e){
                                e.preventDefault();
                                console.log(e);
                                rqst = new XMLHttpRequest();
                                rqst.open('POST', 'login.php');
                                rqst.onload = function(){
                                    errors_clear();
                                    console.log(rqst.response);
                                    let response = JSON.parse(rqst.response);
                                    console.error(response);
                                        if(typeof response.err ==='undefined'){
                                            console.log('has NO error');
                                            $page = prepare_page_data(rqst.response);
                                            draw_page($page);
                                        }else{
                                            
                                            console.error('has error');
                                            errors_show(response.err);
                                        }
                                    //draw_page(page);
                                };
                                let form = new FormData(signin_form);
                                rqst.send(form);
                            }
                        </script>
                    </div>
                </div>
                </div>
            </div>
        <!-- </div>
        <div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" > -->
            <div class="modal-dialog" id="signup_form_container">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h4 class="fw-bold mb-0 fs-2">
                            signup
                        </h4>
                    </div>
                    <div class="modal-body p-5 pt-0">
                        <div class="signup_form">
                            <form action="signup" method="post" id = 'signup_form'>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control rounded-3" name="email" id="email">
                                    <label for="email">email</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control rounded-3" name="name" id="name">
                                    <label for="name">user name</label>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control rounded-3" name="password" id="pass">
                                        <label for="pass">type password</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password_confirm" class="form-control rounded-3" id="pass_confirm">
                                        <label for="pass_confirm">repeat password</label>
                                    </div>
                                    <div class="">
                                        <label for="profile_photo">your photo:</label>
                                        <br>
                                        <br>
                                        <input type="file" name="photo" id="profile_photo" class="form-control rounded-3">
                                        <br>
                                       
                                    </div>
                                    <div class="form-floating mb-3">
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" onclick="signup(event)">signup</button>
                                    </div>
                                </div>
                            </form>
                            <script>
                                function signup(e){
                                    e.preventDefault();
                                    console.log(e);
                                    rqst = new XMLHttpRequest();
                                    rqst.open('POST', 'signup.php');
                                    rqst.onload = function(){
                                        errors_clear();
                                        console.warn(rqst.response);
                                        let response = JSON.parse(rqst.response);
                                        if(typeof response.err ==='undefined'){
                                            console.log('has NO error');
                                            $page = prepare_page_data(rqst.response);
                                            draw_page($page);
                                        }else{
                                            console.error('has error');
                                            errors_show(response.err);
                                        }
                                        // if(typeof response.err ==='undefined'){
                                        //     console.error('has error');
                                        //     console.error(response.err );
                                        // }
                                    };
                                    let form = new FormData(signup_form);
                                    rqst.send(form);
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div>
        <div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" > -->
            <div class='flex-shrink-0' id="user_info_container">
                <div class="container">
                    <h2 class="mt-0"> user profile </h2>
                    <dl>
                        <dt>
                            user name :
                        </dt>
                        <dd>
                            <span id='user_name'></span>
                        </dd>
                        <dt>
                            user email :
                        </dt>
                        <dd>
                            <span id='user_email'></span>
                        </dd>
                        <dt>
                            user photo :
                        </dt>
                        <dd>
                            <img src="" alt="user_photo" id = "user_photo">
                        </dd>
                    </dl>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script>
        //page init
        rqst = new XMLHttpRequest();
        rqst.open('POST', 'status.php');
        rqst.onload = function(){
            console.warn('-------------------rqst.response ----------------');
            console.warn(JSON.parse(rqst.response));
            // let response = JSON.parse(rqst.response);
            let page = prepare_page_data(rqst.response);
             draw_page(page);
        };
        rqst.send();

        function errors_show(err_arr){
            err_arr.forEach(
                (item, index, array)=>{
                    let node = document.createElement('p');
                    node.innerHTML = item;
                    node.className = "p-3 mb-2 bg-danger text-white";
                    errors_dashboard.append(node);
                }
            );
        }
        function errors_clear(){
            while (errors_dashboard.firstChild) {
                errors_dashboard.removeChild(errors_dashboard.lastChild);
            }
        }
        function prepare_page_data(json){
            let response = JSON.parse(json);
            let page = {};
            page.is_auth    = response.is_auth    ;
            page.user_name  = (response.is_auth === true)?response.user.name :null ; 
            page.user_email = (response.is_auth === true)?response.user.email:null ;
            page.user_photo = (response.is_auth === true)?response.user.photo:null ;
            return page;
        }

        function draw_page(page){

            if(page.is_auth === true){
                login_form_container.style.display = 'none';
                signup_form_container.style.display = 'none';
                user_info_container.style = '';
                signin_button.style.display  = 'none';;
                signup_button.style.display  = 'none';
                signout_button.style  = '';
                user_name.innerHTML =   page.user_name;
                user_email.innerHTML =  page.user_email;
                user_photo.src =        page.user_photo; //'img/default.jpg';
            }else {
                login_form_container.style = '';
                signup_form_container.style.display = 'none';
                user_info_container.style.display = 'none';
                signin_button.style  = '';;
                signup_button.style  = '';;
                signout_button.style.display  = 'none';   
            }
        }
    </script>
</body>
</html>