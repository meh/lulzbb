<div id="login-screen">
    <form>
        <span class="parameter">Username</span><br/>
            <input id="username" type="text"/><br/>

        <span class="parameter">Password</span><br/>
            <input id="password" type="password"/><br/>

        <input value="Login" type="submit" 
            onclick="POST('middle', '?input&login', 'username='+urlencode('username')+'&password'+urlencode('password'));"/>
    </form>
</div>
