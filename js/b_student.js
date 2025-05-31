const enrollModal = document.getElementById('enroll_student_modal');
const addPaymentModal = document.getElementById('add_payment_modal');
const editDetailsModal = document.getElementById('edit_details_modal');

const std_info = {
    fname: document.getElementById('fname'),
    mname: document.getElementById('mname'),
    lname: document.getElementById('lname'),
    gender: document.getElementById('gender'),
    bday: document.getElementById('bday'),
    g_fname: document.getElementById('g_fname'),
    g_mname: document.getElementById('g_mname'),
    g_lname: document.getElementById('g_lname'),
    email: document.getElementById('email'),
    phone: document.getElementById('phone'),
    address: document.getElementById('address')
}

function resetForm(){
    for(let i in std_info){
        std_info[i].value = "";
    }
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

//Search Student Information Input Element
const searchStdInfo = document.getElementById('search_std_info');
const displayTuteesInfo = ()=>{
    const info = {
        displayTuteesInfo: true,
        branch: sessionStorage.getItem('branch'),
        search: searchStdInfo.value
    }

    const table = document.getElementById('tutees-info-table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Tutee Info's: ",data);

        if(!data.result){
            const row = document.createElement('div');
            row.innerHTML = `
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full text-sm font-bold text-center">No Result.</p>
                </div>
            `;
            table.append(row);
        }else{
            data.infos.forEach(info=>{
                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex items-center p-2 my-1 rounded border border-blue-500 hover:bg-gray-300">
                        <p class="w-full text-sm font-bold">${info.fname}</p>
                        <p class="w-full text-sm font-bold">${info.mname}</p>
                        <p class="w-full text-sm font-bold">${info.lname}</p>
                        <p class="w-full text-sm font-bold">${info.gender}</p>
                        <p class="w-full text-sm font-bold">${calculateAge(info.bday)}</p>
                        <div class="w-full flex justify-end space-x-3">
                            <button class="p-2 text-xs bg-green-500 rounded opacity-80 hover:opacity-100 enroll_btn" data-id='${info.std_id}'>Enroll</button>
                            <button class="p-2 text-xs bg-blue-500 rounded opacity-80 hover:opacity-100 edit-info" data-id='${info.std_id}'>Edit Details</button>
                            <button class="p-2 text-xs bg-gray-500 rounded opacity-80 hover:opacity-100" data-id='${info.std_id}'>Archive</button>
                        </div>
                    </div>
                `;

                table.append(row);
            })

            const enroll = document.querySelectorAll('.enroll_btn');
            enroll.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    const confirm = document.getElementById('confirm_enroll');
                    confirm.setAttribute('data-id', id);

                    enrollModal.classList.remove('hidden');
                })
            })

            const edit = document.querySelectorAll('.edit-info');
            edit.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    const info = {
                        getTuteeInfoDetails: true,
                        tutee: id
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Get Details: ",data);
                        editDetailsModal.classList.remove('hidden');

                        document.getElementById('e-fname').value = data.tutee.fname;
                        document.getElementById('e-mname').value = data.tutee.mname;
                        document.getElementById('e-lname').value = data.tutee.lname;
                        document.getElementById('e-gender').value = data.tutee.gender;
                        document.getElementById('e-bday').value = data.tutee.bday;
                        document.getElementById('e-gfname').value = data.tutee.g_fname;
                        document.getElementById('e-gmname').value = data.tutee.g_mname;
                        document.getElementById('e-glname').value = data.tutee.g_lname;
                        document.getElementById('e-email').value = data.tutee.email;
                        document.getElementById('e-phone').value = data.tutee.phone;
                        document.getElementById('e-address').value = data.tutee.address;

                        document.getElementById('save_edit_details').setAttribute('data-id', id);
                    })
                    .catch(error => {console.error('Error Message!', error)})
                })
            })

        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
setTimeout(function() {displayTuteesInfo();}, 1500);

//Dynamically change the list base on search
searchStdInfo.onsearch = ()=>{
    displayTuteesInfo();
}

const displayTutees = ()=>{

    const search = document.getElementById('tutee_search');
    const program = document.getElementById('tutee_program');
    const year = document.getElementById('tutee_year');
    const month = document.getElementById('tutee_month');

    search.onsearch = ()=>{displayTutees();}
    program.onchange = ()=>{displayTutees();}
    year.onchange = ()=>{displayTutees();}
    month.onchange = ()=>{displayTutees();}

    const info = {
        displayTutees: true,
        branch: sessionStorage.getItem('branch'),
        search: search.value,
        program: program.value,
        year: year.value,
        month: month.value
    }

    const table = document.getElementById('tutee-table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Monthly Tutees: ",data);

        if(!data.result){
            table.innerHTML = `
                <div class="flex p-2 my-1 rounded border border-blue-500">
                    <p class="w-full font-bold text-center">No Results.</p>
                </div>
            `;
        }else{
            data.tutees.forEach(t=>{
                const row = document.createElement('div');
                let initial = t.mname ? `${t.mname.slice(0, 1).toUpperCase()}.` : "" ;
                row.innerHTML = `
                    <div class="flex items-center p-2 my-1 rounded border border-blue-500 hover:bg-gray-300">
                        <p class="w-full font-bold"> ${t.lname}, ${t.fname} ${initial}</p>
                        <p class="w-full font-bold">${t.program}</p>
                        <div class="w-full flex justify-end space-x-3">
                            <button class="p-2 text-xs bg-yellow-500 rounded opacity-80 hover:opacity-100 payment" data-id='${t.enrolled_id}'>Payment</button>
                            <button class="p-2 text-xs bg-red-500 rounded opacity-80 hover:opacity-100 unenroll" data-id='${t.enrolled_id}'>Unenroll</button>
                        </div>
                    </div>
                `;

                table.append(row);
            })

            const unenroll = document.querySelectorAll('.unenroll');
            unenroll.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    let confirm = window.confirm("Do you want to unenroll this tutee for this date period?");
                    if(!confirm){
                        return;
                    }

                    const info = {
                        unenrollTutee: true,
                        id: id
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Unenroll: ",data);

                        if(!data.result){
                            alert("Something went wrong.");
                        }else{
                            displayTutees();
                        }
                    })
                    .catch(error => {console.error('Error Message!', error)})
                })
            })

            const payment = document.querySelectorAll('.payment');
            payment.forEach(p=>{
                p.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    document.getElementById('confirm_payment').setAttribute('data-id', id)

                    document.getElementById('date_period').textContent = "Date Period: " + month.value + "-" + year.value;

                    addPaymentModal.classList.remove('hidden');
                })
            })
        }
        
    })
    .catch(error => {console.error('Error Message!', error)})


}
setTimeout(function() {displayTutees();}, 1500);

//Add new tutee info
const addInfo = document.getElementById('add_info');
addInfo.onclick = ()=>{

    if(!std_info.fname.value && !std_info.lname.value){
        alert('Firstname and Lastname is strictly required.');
        return;
    }

    const info = {
        addTuteeInfo: true,
        fname: std_info.fname.value,
        mname: std_info.mname.value,
        lname: std_info.lname.value,
        gender: std_info.gender.value,
        bday: std_info.bday.value,
        g_fname: std_info.g_fname.value,
        g_mname: std_info.g_mname.value,
        g_lname: std_info.g_lname.value,
        email: std_info.email.value,
        phone: std_info.phone.value,
        address: std_info.address.value,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Student Information: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            displayTuteesInfo();
            resetForm();
        }
    })
    .catch(error => {console.error('Error Message!', error)})

}

//To display if the program selected has a list
const programSelect = document.getElementById('program_select');
programSelect.onchange = ()=>{
    const list_t = document.getElementById('program_list_table');
    list_t.innerHTML = "";

    const info = {
        displayPrograms: true
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Program List: ", data);

        if(!data.result){
            list_t.innerHTML = "";
        }else{
            const programExists = data.programs.some(program => program.under === programSelect.value);
            if(programExists){
                const select = document.createElement('select');
                select.id = "program_list";
                select.classList.add('w-full');
                select.classList.add('text-xl');
                select.classList.add('p-2');
                select.classList.add('cursor-pointer');
                select.classList.add('border');
                select.classList.add('border-blue-500');
                select.classList.add('rounded');
                select.innerHTML = "";
                select.innerHTML = `<option value=''> Select ${programSelect.value} List </option>`;
                list_t.append(select);

                data.programs.forEach(program=>{
                    if(program.under == programSelect.value){
                        const option = document.createElement('option');
                        option.value = program.program;
                        option.textContent = program.program;

                        select.append(option);
                    }
                    
                })
            }
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

//Enroll Tutee
const confirmEnroll = document.getElementById('confirm_enroll');
confirmEnroll.onclick = ()=>{
    const id = confirmEnroll.getAttribute('data-id');

    const year = document.getElementById('enroll_year');
    const month = document.getElementById('enroll_month');
    const program = document.getElementById('program_select');
    const p_list = document.getElementById('program_list') ? document.getElementById('program_list') : "";
    const rates = document.getElementById('enroll_rates');

    if(!rates.value){
        alert('You need to enter the Rates Amount.');
        return;
    }

    if(document.getElementById('program_list')){
        if(!p_list.value){
            alert('You need to select the list under the program you selected');
            return;
        }
    }

    const info = {
        enrollTutee: true,
        id: id,
        year: year.value,
        month: month.value,
        program: p_list.value ? p_list.value : program.value,
        rates: rates.value,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Tutee: ",data);
        if(!data.result){
            alert(data.message);
        }else{
            enrollModal.classList.add('hidden');
            displayTutees();
        }
    })
    .catch(error => {console.error('Error Message!', error)})

}

//Add Payment
const confirmPayment = document.getElementById('confirm_payment');
confirmPayment.onclick = ()=>{

    const amount = document.getElementById('payment_amount');

    const info = {
        addPayment: true,
        id: confirmPayment.getAttribute('data-id'),
        amount: amount.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Payment: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            addPaymentModal.classList.add('hidden');
            amount.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

//Save Changes in Edit Tutee Details
document.getElementById('save_edit_details').onclick = e=>{
    const id = e.currentTarget.getAttribute('data-id');

    const info = {
        editTuteeInfo: true,
        id: id,
        fname: document.getElementById('e-fname').value,
        mname: document.getElementById('e-mname').value,
        lname: document.getElementById('e-lname').value,
        gender: document.getElementById('e-gender').value,
        bday: document.getElementById('e-bday').value,
        gfname: document.getElementById('e-gfname').value,
        gmname: document.getElementById('e-gmname').value,
        glname: document.getElementById('e-glname').value,
        email: document.getElementById('e-email').value,
        phone: document.getElementById('e-phone').value,
        address: document.getElementById('e-address').value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Save Edit Tutee Details: ", data);

        data ? console.log("Edit Successfully") : console.log('Edit Failed');
        displayTuteesInfo();
        displayTutees();
        editDetailsModal.classList.add('hidden');
    })
    .catch(error => {console.error('Error Message!', error)})
}

//Close all popup
const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        enrollModal.classList.add('hidden');
        addPaymentModal.classList.add('hidden');
        editDetailsModal.classList.add('hidden');
    })
})