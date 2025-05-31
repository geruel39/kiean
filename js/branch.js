const addAccount = document.getElementById('add_account');
const addRole = document.getElementById('add_role');
const addBranch = document.getElementById('add_branch');

const accountModal = document.getElementById('account_modal');
const roleModal = document.getElementById('role_modal');
const branchModal = document.getElementById('branch_modal');
const selectAccountModal = document.getElementById('select_account_modal');

const editBranchModal = document.getElementById('edit_branch_modal');

addAccount.onclick = ()=>{
    accountModal.classList.remove('hidden');
}
addRole.onclick = ()=>{
    roleModal.classList.remove('hidden');
}
addBranch.onclick = ()=>{
    branchModal.classList.remove('hidden');
}


const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        accountModal.classList.add('hidden');
        roleModal.classList.add('hidden');
        branchModal.classList.add('hidden');
        selectAccountModal.classList.add('hidden');
        editBranchModal.classList.add('hidden');
    })
})

const displayBranches = ()=>{
    const info = {
        displayBranches: true
    }

    const table = document.getElementById('branch-table');
    table.innerHTML = '';

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Branches: ",data);

        if(!data.result){

            const row = document.createElement('div');
            row.innerHTML = `<div class="flex items-center p-2 my-1 rounded border border-blue-500"><p class="w-full text-xl font-bold text-center">No Result</p></div>`;
            table.append(row);
            
        }else{
            data.branches.forEach(branch=>{
                
                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex items-center p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-xl font-bold">${branch.name}</p>
                        <p class="w-full font-bold">${branch.commission}</p>
                        <p class="w-full font-bold">${branch.location}</p>
                        <p class="w-full text-2xl text-center font-bold cursor-pointer hover:text-blue-600 has_access" data-id='${branch.branch_id}'>${branch.access}</p>
                        <div class="w-full flex justify-around">
                            <button class='p-1 bg-green-500 rounded opacity-80 hover:opacity-100 add_access' data-id='${branch.branch_id}'>Add Access</button>
                            <button class='p-1 bg-blue-500 rounded opacity-80 hover:opacity-100 edit-branch-details' data-id='${branch.branch_id}'>Edit Details</button>
                            <button class='p-1 bg-red-500 rounded opacity-80 hover:opacity-100' data-id='${branch.branch_id}'>Delete</button>
                        </div>
                    </div>
                `

                table.append(row);
            })

            //Add access btn
            const accessBtn = document.querySelectorAll('.add_access');
            accessBtn.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const branch = e.currentTarget.getAttribute('data-id');

                    selectAccountModal.classList.remove('hidden');

                    const table = document.getElementById('select_account');
                    table.innerHTML = "";

                    const info = {
                        selectAccount: true,
                        branch: branch
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Select Account: ",data);

                        if(!data.result){
                            const row = document.createElement('div');
                            row.innerHTML = `
                            <div class="flex border border-blue-500 p-1 my-1 rounded cursor-pointer hover:bg-gray-400">
                                <p class="w-full font-bold text-center">No Result</p>
                            </div>
                            `
                            table.append(row);
                        }else{
                            data.accounts.forEach(acc=>{
                                const row = document.createElement('div');
                                row.innerHTML = `
                                <div class="flex border border-blue-500 p-1 my-1 rounded cursor-pointer hover:bg-gray-400 give_access" data-id='${acc.account_id}'>
                                    <p class="w-full font-bold text-center">${acc.username}</p>
                                    <p class="w-full font-bold text-center">${acc.role}</p>
                                </div>
                                `
                                table.append(row);
                            })

                            const access = document.querySelectorAll('.give_access');
                            access.forEach(btn=>{
                                btn.addEventListener('click', e=>{
                                    const account = e.currentTarget.getAttribute('data-id');

                                    const info = {
                                        giveAccess: true,
                                        account: account,
                                        branch: branch
                                    }

                                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                                    .then(response => response.json())
                                    .then(data =>{
                                        console.log( "Give Access: ",data);
                                        if(!data.result){
                                            alert('Something went wrong.');
                                        }else{
                                            selectAccountModal.classList.add('hidden');
                                            displayBranches();
                                        }
                                    })
                                    .catch(error => {console.error('Error Message!', error)})
                                })
                            })
                        }


                    })
                    .catch(error => {console.error('Error Message!', error)})
                    
                })
            })

            //Remove access btn
            const hasAccess = document.querySelectorAll('.has_access');
            hasAccess.forEach(btn=>{
                btn.addEventListener('click', e=>{

                    const branch = e.currentTarget.getAttribute('data-id');

                    selectAccountModal.classList.remove('hidden');

                    const table = document.getElementById('select_account');
                    table.innerHTML = "";

                    const info = {
                        selectRemoveAccount: true,
                        branch: branch
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Select to Remove: ",data);

                        if(!data.result){
                            const row = document.createElement('div');
                            row.innerHTML = `
                            <div class="flex border border-blue-500 p-1 my-1 rounded cursor-pointer hover:bg-gray-400">
                                <p class="w-full font-bold text-center">No Result</p>
                            </div>
                            `
                            table.append(row);
                        }else{
                            data.accounts.forEach(acc=>{
                                const row = document.createElement('div');
                                row.innerHTML = `
                                <div class="flex border border-blue-500 p-1 my-1 rounded cursor-pointer hover:bg-gray-400 remove_access" data-id='${acc.account_id}'>
                                    <p class="w-full font-bold text-center">${acc.username}</p>
                                    <p class="w-full font-bold text-center">${acc.role}</p>
                                </div>
                                `
                                table.append(row);
                            })

                            const removeAccess = document.querySelectorAll('.remove_access');
                            removeAccess.forEach(btn=>{
                                btn.addEventListener('click', e=>{
                                    const account = e.currentTarget.getAttribute('data-id');

                                    const info = {
                                        removeAccess: true,
                                        account: account,
                                        branch: branch
                                    }

                                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                                    .then(response => response.json())
                                    .then(data =>{
                                        console.log( "Give Access: ",data);
                                        if(!data.result){
                                            alert('Something went wrong.');
                                        }else{
                                            selectAccountModal.classList.add('hidden');
                                            displayBranches();
                                        }
                                    })
                                    .catch(error => {console.error('Error Message!', error)})

                                })
                            })


                        }
                    })
                    .catch(error => {console.error('Error Message!', error)})

                    
                })
            })

            //Edit Details
            const edit = document.querySelectorAll('.edit-branch-details');
            edit.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    const info = {
                        getBranchDetails: true,
                        id: id
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Get Branch Details: ",data);

                        editBranchModal.classList.remove('hidden');

                        document.getElementById('e-name').value = data.branch.name;
                        document.getElementById('e-commission').value = data.branch.commission;
                        document.getElementById('e-location').value = data.branch.location;

                        document.getElementById('save_edit_details').setAttribute('data-id', id);
                    })
                    .catch(error => {console.error('Error Message!', error)})
                })
            })



        }

    })
    .catch(error => {console.error('Error Message!', error)})
}

//Save Branch Edit
document.getElementById('save_edit_details').onclick = e=>{
    const id = e.currentTarget.getAttribute('data-id');

    const info = {
        editBranchDetails: true,
        id: id,
        name: document.getElementById('e-name').value,
        commission: document.getElementById('e-commission').value,
        location: document.getElementById('e-location').value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Save Branch Edit: ",data);
        data ? console.log('Branch Edit Success') : console.log('Branch Edit Failed');
        displayBranches();
        editBranchModal.classList.add('hidden');
    })
    .catch(error => {console.error('Error Message!', error)})

}

displayBranches();

const addBranchBtn = document.getElementById('add_branch_btn');
addBranchBtn.onclick = ()=>{
    const name = document.getElementById('branch_name');
    const commission = document.getElementById('commission');
    const location = document.getElementById('location');

    if(!name.value){
        alert('Enter branch name');
        return;
    }

    if(!commission.value){
        alert('Enter commision percent');
        return;
    }

    if(!location.value){
        alert('Enter branch location');
        return;
    }

    const info = {
        addNewBranch: true,
        name: name.value,
        commission: commission.value,
        location: location.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Fetch Result: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            displayBranches();
            branchModal.classList.add('hidden');
            name.value = "";
            commission.value = "";
            location.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

const insertRoles = ()=>{
    const info = {
        insertRoles: true
    }

    const roles = document.getElementById('role');
    roles.innerHTML = `<option value='' selected disabled>Select Role</option>`;

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Roles: ",data);

        if(!data.result){
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "No Roles Available";
            roles.append(option);
        }else{
            data.roles.forEach(role=>{
                const option = document.createElement('option');
                option.value = role.role;
                option.textContent = role.role;
                roles.append(option);
            })
        }
        
    })
    .catch(error => {console.error('Error Message!', error)})
}

insertRoles();
//No delete role
//No edit role

const addRoleBtn = document.getElementById('add_role_btn');
addRoleBtn.onclick = ()=>{
    const role = document.getElementById('role_title');

    if(!role.value){
        alert('Enter the role.');
        return;
    }

    role.value = role.value.charAt(0).toUpperCase() + role.value.slice(1).toLowerCase();

    if (role.value === "Admin") {
        alert('This role is not allowed.')
        return;
    }
    

    const info = {
        addNewRole: true,
        role: role.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Fetch Result: ",data);
        if(!data.result){
            alert(data.message);
        }
        else{
            insertRoles();
            roleModal.classList.add('hidden');
            role.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)});
}

const displayAccounts = ()=>{
    const info = {
        displayAccounts: true
    }

    const table = document.getElementById('account-table');
    table.innerHTML = '';

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Accounts: ",data);

        if(!data.result){
            const row = document.createElement('div');
            row.innerHTML = `<div class="flex items-center p-2 my-1 rounded border border-blue-500"><p class="w-full text-xl font-bold text-center">No Result</p></div>`;
            table.append(row);
        }else{
            data.accounts.forEach(acc=>{
                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-xl font-bold">${acc.username}</p>
                        <p class="w-full text-xl font-bold">${acc.role}</p>
                        <div class="w-full flex justify-end space-x-3">
                            <button class='px-2 bg-blue-500 rounded opacity-80 hover:opacity-100' data-id='${acc.account_id}'>Edit</button>
                            <button class='px-2 bg-red-500 rounded opacity-80 hover:opacity-100 delete_acc' data-id='${acc.account_id}'>Delete</button>
                        </div>
                    </div>
                `

                table.append(row);
            })
            
            const deleteAcc = document.querySelectorAll('.delete_acc');
            deleteAcc.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const account = e.currentTarget.getAttribute('data-id');

                    let confirm = window.confirm('Do you want to delete this account?');
                    if(!confirm){
                        return;
                    }

                    const info = {
                        deleteAcc: true,
                        account: account
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Delete Account: ",data);
                        if(!data.result){
                            alert('Something went wrong this account maybe has access to a branch. Remove the access to delete.');
                        }else{
                            displayAccounts();
                        }
                    })
                    .catch(error => {console.error('Error Message!', error)})
                })
            })


        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

displayAccounts();
//No delete
//No edit

const addAccountBtn = document.getElementById('add_account_btn');
addAccountBtn.onclick = ()=>{
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const password2 = document.getElementById('password2');
    const role = document.getElementById('role');

    if(!username.value && !password.value && !password2.value && !role.value){
        alert('Fill all the fields.');
        return;
    }

    if(password.value != password2.value){
        alert('Password do not match.');
        return;
    }

    const info = {
        addNewAccount: true,
        username: username.value,
        password: password.value,
        role: role.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Fetch Result: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            displayAccounts();
            accountModal.classList.add('hidden');
            username.value = "";
            password.value = "";
            password2.value = "";
            role.value = "";
        }
        
    })
    .catch(error => {console.error('Error Message!', error)})
}