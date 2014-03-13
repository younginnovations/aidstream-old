<style>
    #static-header
    {
        position: fixed;
        width: 100%;
        z-index: 100;
    }

    .nav ul {
        padding-top: 12px;
    }


    .login {
        margin-top: 6px;
    }
      
    #login-form-wrapper {
        top: 67px;
    }
    
</style>
<script type="text/javascript" src="js/jquery1-9.js"></script>
<script type="text/javascript" src= "js/common.js"></script>
<div id="static-header">
    <div id="home-user-nav">
        <div class="container_12">
            <?php if($_SESSION['Zend_Auth']): ?>
            <div class="nav_container">
                <?php if($_SESSION['superadmin']['identity']): ?>
                <?php //echo $this->l('Switch back', 'user/user/switch-back')?>
                <a href='/user/user/switch-back'>Switch back</a>,
                You are masquerading as  <a href="/user/user/myaccount" class='user-name'><?php print $_SESSION['Zend_Auth']['storage']->user_name;?></a>
                |
                <a href="/wep/dashboard">Dashboard</a>
                <?php print " , (<a href='/user/user/logout'>Logout</a>)";?>
                <?php else : ?>
                You are logged in as  <a href="/user/user/myaccount" class='user-name'><?php print $_SESSION['Zend_Auth']['storage']->user_name;?></a>
                |
                <?php if($identity->role_id == 3): ?>
                <a href="/admin/dashboard">Dashboard</a>
                <?php else : ?>
                <a href="/admin/dashboard">Dashboard</a>
                <?php endif; ?>
                <?php print " , (<a href='/user/user/logout'>Logout</a>)";?>
                <?php endif;?>
            </div>
            <? endif;?>
        </div>
    </div>
    <div class="header-container">

        <div class="header">
            <div class="logo">
                <a href="/" title="aidstream"><img src="img/logo.png" alt="Aidstream"></a>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="/about">About</a></li>
                    <li class="active-menu"><a href="/how-to">How to Use</a></li>
                    <li><a href="/organisations">Who's Using</a></li>
                    <li><a href="/snapshot">Snapshot</a></li>
                    <li><a href="http://blog.aidstream.org" target="_blank">Blog</a></li>
                    <li id="demo-link"><a href="http://demo.aidstream.org" target="_blank">Demo</a></li>
                </ul>

                <?php if(!$_SESSION['Zend_Auth']): ?>
                <div class="login" id="login-register-popup">
                    <a href="#">Login</a> / <a href="#">Register</a>
                </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
    <div class="login-wrapper">
        <div id="login-form-wrapper" style="display:none">
            <form method="post" action="/user/user/login" id="user-login">
                <table>
                    <tbody>
                        <tr>
                            <td><input type="text" name="username" id="username" value="" placeholder="Username" class="input_box username form-text"><span id="username-error" class="error"></span></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="password" id="password" value="" placeholder="Password" class="input_box password form-text"><span id="password-error" class="error"></span></td>
                        </tr>
                        <tr>
                            <td><input type="submit" name="login" id="login" value="Secure Login"></td>
                        </tr>
                        <tr> <td><div class="login-register">Not registered yet? <a href="/user/user/register">Register now</a> </div><div class="forgot-password"><a href="/user/user/forgotpassword">Forgot Password?</a></div></td></tr>
                    </tbody></table>
            </form>
        </div><!--Close login-form-container-->
    </div>
</div>


