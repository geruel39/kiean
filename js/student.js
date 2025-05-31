const addProgram = document.getElementById('add_program');
const addUnder = document.getElementById('add_under');

const programModal = document.getElementById('program_modal');
const underModal = document.getElementById('under_modal');

addProgram.onclick = ()=>{
    programModal.classList.remove('hidden');
}
addUnder.onclick = ()=>{
    underModal.classList.remove('hidden');
}

const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        programModal.classList.add('hidden');
        underModal.classList.add('hidden');
    })
})

const displayProgram = ()=>{
    const info = {
        displayPrograms: true
    }

    const pTable = document.getElementById('programs_table');  
    pTable.innerHTML = "";  

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Programs: ",data);

        if(!data.result){
            const row = document.createElement('div');
            row.innerHTML = `
                    <div class="flex py-3 my-1 rounded border border-blue-500">
                        <p class="w-full text-2xl font-bold p-1">No Result.</p>
                    </div>
            `

            pTable.append(row);
        }else{

            
            data.programs.forEach(program=>{

                if(program.under == 'None'){

                    const row = document.createElement('div');
                    row.innerHTML = `
                        <div class="flex py-3 my-1 rounded border border-blue-500">
                            <p class="w-full text-xl font-bold p-1">${program.program}</p>
                            <div class="w-full" id='${program.program}-list'>
                                
                            </div>

                        </div>
                    `;
                    pTable.append(row);
                }else{

                    const list = document.getElementById(`${program.under}-list`);

                    const plist = document.createElement('p');
                    plist.classList.add('w-full');
                    plist.classList.add('text-md');
                    plist.classList.add('font-bold');
                    plist.classList.add('p-1');
                    plist.textContent = program.program;

                    list.append(plist);

                }


            })
        }

    })
    .catch(error => {console.error('Error Message!', error)})
}
displayProgram();

const insertProgram = ()=>{
    const info = {
        insertProgram: true
    } 

    const select = document.getElementById('under_program');
    select.innerHTML = "<option value=''>Select Program</option>";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Insert Program: ",data);
        if(!data.result){
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "No Program Available";

            select.append(option);
        }else{
            data.programs.forEach(program=>{
                const option = document.createElement('option');
                option.value = program.program;
                option.textContent = program.program;

                select.append(option);
            })
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
insertProgram();

const addProgramBtn = document.getElementById('add_program_btn');
addProgramBtn.onclick = ()=>{
    const program = document.getElementById('program');

    if(!program.value){
        alert('Enter the program title');
        return;
    }

    const info = {
        addProgram: true,
        program: program.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Program: ",data);

        if(!data.result){
            alert("Something went wrong.");
        }else{
            insertProgram();
            displayProgram();
            programModal.classList.add('hidden');
            program.value = "";
            console.log("Program Added");
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

const addProgramList = document.getElementById('add_proglist');
addProgramList.onclick = ()=>{
    const under = document.getElementById('under_program');
    const list = document.getElementById('program_list');

    if(!under.value){
        alert('Select Program.');
        return;
    }

    if(!list.value){
        alert('Enter program list.');
        return;
    }

    const info = {
        addProgramList: true,
        program: list.value,
        under: under.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Program List: ",data);

        if(!data.result){
            alert('Something went wrong.');
        }else{

            console.log('Program List Added');
            displayProgram();
            underModal.classList.add('hidden');
            under.value = "";
            list.value = "";

        }
    })
    .catch(error => {console.error('Error Message!', error)})


}

function calculateAge(dob) {
    if (dob === "0000-00-00") {
      return "";
    }
  
    const birthDate = new Date(dob);
    const today = new Date();
    
    let age = today.getFullYear() - birthDate.getFullYear();
    
    const monthDifference = today.getMonth() - birthDate.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
  
    return age.toString();
} 

function displayTuteeList(){

    const year = document.getElementById('tutee_year');
    const month = document.getElementById('tutee_month');

    year.onchange = ()=>{displayTuteeList();}    
    month.onchange = ()=>{displayTuteeList();} 

    const info = {
        displayTuteeList_s: true,
        year: year.value,
        month: month.value
    }

    const table = document.getElementById('tutee-table');
    table.innerHTML = '';

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Tutee List: ",data);
        if(!data.result){
            table.innerHTML = `<div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full font-bold text-center">No Results.</p>
                </div>`;
        }else{
            data.tutees.forEach(t=>{
                const row = document.createElement('div');
                row.innerHTML = `
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full font-bold">${t.fname} ${t.mname} ${t.lname}</p>
                    <p class="w-full font-bold">${t.program}</p>
                    <p class="w-full font-bold">${t.rates}</p>
                    <p class="w-full font-bold">${t.paid}</p>
                </div>
                `;

                table.append(row);
            })
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
displayTuteeList();
function displayTuteeListInfo(){

    const search = document.getElementById('info_search');
    const branch = document.getElementById('info_branch');

    search.onsearh = ()=>{displayTuteeListInfo();}
    branch.onchange = ()=>{displayTuteeListInfo();}

    const info = {
        displayTuteeListInfo_s: true,
        search: search.value,
        branch: branch.value
    }

    const table = document.getElementById('tutees-info-table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Tutee Information: ",data);

        if(!data.result){
            table.innerHTML = `
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full text-sm font-bold text-center">No Results.</p>
                </div>
            `;
        }else{
            data.infos.forEach(i=>{
                const row = document.createElement('div');
                row.innerHTML = `
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full text-sm font-bold">${i.fname} ${i.mname} ${i.lname}</p>
                    <p class="w-full text-sm font-bold">${i.gender}</p>
                    <p class="w-full text-sm font-bold">${calculateAge(i.bday)}</p>
                    <p class="w-full text-sm font-bold">${i.g_fname} ${i.g_mname} ${i.g_lname}</p>
                    <p class="w-full text-sm font-bold">${i.email}</p>
                    <p class="w-full text-sm font-bold">${i.name}</p>
                </div>
                `

                table.append(row);
            })
        }

    })
    .catch(error => {console.error('Error Message!', error)})
}
displayTuteeListInfo();