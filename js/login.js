const username = document.getElementById('username');
const password = document.getElementById('password');
const login = document.getElementById('login');

login.onclick = ()=>{
    
    if(!username.value){
        alert('Enter username!');
        return;
    }

    if(!password.value){
        alert('Enter password!');
        return;
    }

    const info = {
        userLogIn: true,
        username: username.value,
        password: password.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Fetch Result: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            if(data.role == 'Admin'){
                location.href = "dashboard.php";
            }
            else{
                location.href = "b_dashboard.php";
            }
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

