//Open Sidebar
const burger = document.getElementById('burger-icon');
burger.onclick = ()=>{
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.remove('hidden');
}

//Close Sidebar
const times = document.getElementById('times-icon');
times.onclick = ()=>{
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.add('hidden');
}

//Tabs and Pages
const tabs = document.querySelectorAll('.tab');
const pages = document.querySelectorAll('.page');
tabs.forEach((tab,index)=>{
    tab.addEventListener('click', ()=>{
        tabs.forEach(tab=>{tab.classList.remove('bg-blue-500')});
        tab.classList.add('bg-blue-500');

        pages.forEach(page=>{page.classList.add('hidden')});
        pages[index].classList.remove('hidden');
    })
})

//Insert option to all year select input 2020-current year
function populateYearSelect() {
    const yearSelects = document.querySelectorAll('.years');
    const currentYear = new Date().getFullYear();
    
    // Loop through each <select> element with the class 'years'
    yearSelects.forEach(y => {
      // Loop from 2020 to the current year
      for (let year = 2020; year <= currentYear; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        y.appendChild(option);
      }

      y.value = currentYear;
    });
}
populateYearSelect();
function setMonthSelect() {
    const monthSelect = document.querySelectorAll('.months');
    const month = new Date().getMonth() + 1;
    const currentMonth = month < 10 ? '0' + month : month;

    monthSelect.forEach(m=>{
        m.value = currentMonth;
    })
}
setMonthSelect();

function populateProgramSelect() {
    const program_selects = document.querySelectorAll('.programs');

    
    program_selects.forEach(program_select => {
        program_select.innerHTML = "";

        const programTuteesList = document.getElementById('tutee_program');
        programTuteesList.innerHTML = "<option value=''>Any Program</option>"; //Special Consideration for the tutees list
    
        const info = {
            displayPrograms: true
        };

        fetch('z_process.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(info) })
            .then(response => response.json())
            .then(data => {
                console.log("Insert Programs: ", data);

                if (!data.result) {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No Program Available";
                    program_select.append(option);
                } else {
                    data.programs.forEach(program => {
                        if (program.under == 'None') {
                            const option = document.createElement('option');
                            option.value = program.program;
                            option.textContent = program.program;
                            program_select.append(option);
                        }
                    });
                }
            })
            .catch(error => { console.error('Error Message!', error) });
    });

}
populateProgramSelect();


//Logout Button
const logout = document.querySelectorAll('.logout');
logout.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const info = {
            logout: true
        }
    
        fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
        .then(response => response.json())
        .then(data =>{
            console.log( "Logout: ",data);
            if(data.result){
                location.href = "default.php";
            }
        })
        .catch(error => {console.error('Error Message!', error)})
    })
})

//User ID 
const userID = document.getElementById('session_id').value;
const getLocationBranch = ()=>{

    const info = {
        getBranch: true,
        user: userID
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Current branch: ",data);

        if(!data.result){
            alert(data.message);
        }
        else{
            const branchDisplay = document.getElementById('header_branch_display');
            branchDisplay.innerHTML = data.branch.name;
            sessionStorage.setItem('branch', data.branch.branch_id);
        }
    })
    .catch(error => {console.error('Branch location error', error)})
}

//Get All access branch
const getBranchList = ()=>{
    const info = {
        getBranchList: true,
        user: userID
    }

    const table = document.getElementById('change_branch_list');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Change Branch List: ",data);

        if(!data.result){
            table.innerHTML = "<p class='w-full py-2 text-center'>No Result</p>";
        }else{
            data.branch.forEach(n=>{
                const row = document.createElement('div');
                row.innerHTML = `
                    <p class="w-full py-2 cursor-pointer border border-blue-500 text-center rounded hover:bg-gray-300 mb-1 swap_branch" data-id='${n.branch}'>${n.name}</p>
                `

                table.append(row);
            })
        }

        const swap = document.querySelectorAll('.swap_branch');
        swap.forEach(btn=>{
            btn.addEventListener('click', e=>{
                const branch = e.currentTarget.getAttribute('data-id');

                const info = {
                    swapBranch: true,
                    branch: branch,
                    user: userID
                }

                fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                .then(response => response.json())
                .then(data =>{
                    console.log( "Swap branch: ",data);
                    
                    if(!data.result){
                        alert('Something went wrong...');
                    }else{
                        location.reload();
                    }
                })
                .catch(error => {console.error('Error Message!', error)})
            })
        })

    })
    .catch(error => {console.error('Error Message!', error)})
}

getLocationBranch();
getBranchList();

const myBranches = document.getElementById('my_branches');
myBranches.onclick = ()=>{
    const changeBranchModal = document.getElementById('change_branch_modal');

    if(changeBranchModal.classList.contains('hidden')){
        changeBranchModal.classList.remove('hidden');
    }else{
        changeBranchModal.classList.add('hidden');
    }
}