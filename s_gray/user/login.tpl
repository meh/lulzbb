<div id="login-screen">
    <form>
        <span class="parameter">Username</span><br/>
            <input id="username" type="text"/><br/>

        <span class="parameter">Password</span><br/>
            <input id="password" type="password"/><br/>

        <input value="Login" type="submit" 
            onclick="doLogin('middle', getContent('username'), getContent('password'));"/>
    </form>
</div>
