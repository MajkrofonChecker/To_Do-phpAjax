<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <?php 
        include "server.php";
    ?>
</head>
<body>

<!-- DUGMAD -->
    <div id="btn_wrapper">  
        <div class="btn-group" role="group" aria-label="F">
            <button type="button" class="btn btn-secondary inline" value="duties_button" onclick="showBlock(this)">Unesi novu obavezu</button>
            <button type="button" class="btn btn-secondary inline" value="duties_button-update" onclick="showBlock(this)">Izmeni postojecu obavezu</button>
            <button type="button" class="btn btn-secondary inline" value="types_button" onclick="showBlock(this)">Unesi novi tip obaveze</button>
            <button type="button" class="btn btn-secondary inline" value="types_button-update" onclick="showBlock(this)">Izmeni postojeci tip obaveze</button>
        </div>
    </div>
    
<!-- UNESI NOVU OBAVEZU -->
    <div id="duties-block" class="form_block">
        
        <form action="" method="post" id="insert-duty-form">
            <div class="form-group">
                <label for="duty-date">Izaberite datum</label>
                <input class="form-control" type="date" name="duty-date" id="duty-date">
            </div>

            <div class="form-group">
                <label for="duty-time">Izaberite vreme</label>
                <input class="form-control" type="time" name="duty-time" id="duty-time">
            </div>

            <div class="form-group">
                <label for="duty-type">Tip obaveze</label>
                <select class="form-control" name="duty-type" id="duty-type">
                    <!-- listType() -->
                </select>
            </div>

            <div>
                <label for="duty-description">Tekst obaveze</label>
                <textarea class="form-control" name="duty-description" id="duty-description" cols="30" rows="2"></textarea>
            </div>

            <input type="button" name="duty-insert" id="duty-insert" value="Unesi stavku" onclick="insertDuty(this)">
        </form>    

    </div>

<!-- IZMENI POSTOJECU OBAVEZU-->
    <div id="duties-block-update" class="form_block">
        
        <form action="" method="post">
            <div class="form-group">              
                <select name="duty-to-update" id="duty-to-update">
                            <option value="-1">Odaberite obavezu za izmenu:</option>
                    <?php
                        $mydb->select("duties", "duties.id, duties.date, duties.time, duties.description", null, null, null, null, null);
                        while($row = $mydb->getResult()->fetch_object()):
                    ?>
                            <option value="<?php echo $row->id; ?>"> <?php echo $row->date. ' | '. $row->time. ' | '. $row->description; ?> </option>
                    <?php endwhile;?>
                </select>
                <input type="button" onclick="updateDuty()" value="Odaberi">
            </div>
            
            <input type="number" name="duty-id-update" id="duty-id-update" value="" style="display: none;">

            <div class="form-group">
                <label for="duty-date-update">Izaberite novi datum</label>
                <input class="form-control" type="date" name="duty-date-update" id="duty-date-update">
            </div>

            <div class="form-group">
                <label for="duty-time-update">Izaberite novo vreme</label>
                <input class="form-control" type="time" name="duty-time-update" id="duty-time-update">
            </div>

            <div class="form-group">
                <label for="duty-type-update">Novi tip obaveze</label>
                <select class="form-control" name="duty-type-update" id="duty-type-update">
                    <!-- listType() -->
                </select>
            </div>

            <div>
                <label for="duty-description-update">Novi tekst obaveze</label>
                <textarea class="form-control" name="duty-description-update" id="duty-description-update" cols="30" rows="3"></textarea>
            </div>

            <input type="submit" name="duty-update" id="duty-update" value="Izmeni stavku">
        </form>    

    </div>

<!-- UNESI NOVI TIP OBAVEZE-->
    <div id="types-block" class="form_block">

        <form action="" method="POST">
            <label for="type-name">Unesite naziv tipa obaveze</label>
            <input type="text" name="type-name" id="type-name">
            <input type="button" name="type-insert" id="type-insert" value="Unesi tip" onclick="insertType(this)">
        </form>
        
        <table class="table table-light" id="table-get-types">
        
        </table>

    </div>

<!-- IZMENI POSTOJECI TIP OBAVEZE -->
    <div id="types-block-update" class="form_block">

        <form action="" method="POST">
            <div class="form-group">
                <select class="form-control" name="type-id-update" id="type-id-update">
                    <!-- listType() -->
                </select>
            </div>

            <div class="form-group">
                <label for="type-name-update">Unesite novi naziv tipa obaveze</label>
                <input type="text" name="type-name-update" id="type-name-update">
            </div>

            <input type="button" name="type-update" id="type-update" value="Izmeni tip" onclick="updateType(this)">
        </form>

    </div>

<!-- PRIKAZ OBAVEZA -->
    <div id="get_duties" class="justify-content-center">

        <label for="schedule">Pretraga obaveza po datumu</label>
        <input type="date" name="schedule" id="schedule">
        <button onclick="searchByDate()">Pretrazi</button>

        <table class="table" id="table-get">

        </table>      
    </div>

</body>

<script>
    var arrayOfBlocks= ["duties-block", "duties-block-update", "types-block", "types-block-update"];

    window.onload = hideBlocks(), display(), displayType(), listType();

    function hideBlocks(){
        for(const block of arrayOfBlocks){
            document.getElementById(block).style.display = "none"
        }
    }

    function scratchOff(elem){
        elem.parentNode.parentNode.classList.toggle("table-danger")
    }

    function showBlock(elem){
        switch(elem.value){ 
            case "duties_button":
                hideBlocks()
                document.getElementById(arrayOfBlocks[0]).style.display = "block"
                break
            case "duties_button-update":
                hideBlocks()
                document.getElementById(arrayOfBlocks[1]).style.display = "block"
                break
            case "types_button":
                hideBlocks()
                document.getElementById(arrayOfBlocks[2]).style.display = "block"
                break
            case "types_button-update":
                hideBlocks()
                document.getElementById(arrayOfBlocks[3]).style.display = "block"
                break
            default:
                console.log("default")
                break
        }
    }    


//CRUD preko ajax-a 

    function displayType(){
        $.get("json/types.php", function(d){
            let data = JSON.parse(d)
            let t = document.getElementById("table-get-types")
            t.innerHTML = ""
            for(let i = 0; i < data.length; i++){
                let elem = data[i]
                let r = t.insertRow();
                r.classList = "table-secondary"

                let c1 = r.insertCell();
                let t1 = document.createTextNode(elem["name"])

                let c2 = r.insertCell();
                let b1 = document.createElement("input")
                b1.type = "button"
                b1.value = "Obrisi tip"
                b1.onclick = function(){ deleteType(elem["id"])}
                
                c1.appendChild(t1);
                c2.appendChild(b1);
            }
        })
    }

    function listType() {
        var s1 = document.getElementById("duty-type")
        var s2 = document.getElementById("duty-type-update")
        var s3 = document.getElementById("type-id-update")

        optionsInSelect(s1)
        optionsInSelect(s2)
        optionsInSelect(s3)
    }

    function optionsInSelect(obj){
        $.get("json/types.php", function(d){
            let data = JSON.parse(d)
            obj.innerHTML = ""
            let ost = document.createElement("option")
            ost.value = -1
            ost.innerHTML = "Izaberite tip: "
            obj.appendChild(ost)
            for(let elem of data){
                let o = document.createElement("option")
                o.value = elem["id"]
                o.innerHTML = elem["name"]

                obj.appendChild(o)
            }
        })
    }

    function searchByDate(){
        let date = document.querySelector('input[name="schedule"]').value
        display(date);
    }

    function display(date){
        $.get("json/duties.php", function(d){
            let data = JSON.parse(d)
            data = sortData(data)
            let t = document.getElementById("table-get")
            t.innerHTML = ""
            for(let i = 0; i < data.length; i++){
                let elem = data[i]
                if(date == null){
                    let r = t.insertRow();
                    r.classList = "table-success"
                    let duty_date = elem.date + ' ' + elem.time
                    duty_date = new Date(duty_date)
                    now = new Date()
                    if(duty_date<now){
                        r.classList = "table-warning"
                    }

                    let c1 = r.insertCell();
                    let t1 = document.createTextNode(elem["date"] + '  /  ' + elem["time"])

                    let c2 = r.insertCell();
                    let t2 = document.createTextNode(elem["type"] + '  /  ' + elem["description"])

                    let c3 = r.insertCell();
                    let b1 = document.createElement("input");
                    b1.type = "button"
                    b1.value = "Uradjeno"
                    b1.onclick = function() {scratchOff(this)}
                    let i1 = document.createElement("input");
                    i1.type = "submit"
                    i1.onclick = function() {deleteDuty(elem["id"])}
                    i1.value = "Obrisi"
                    
                    c1.appendChild(t1);
                    c2.appendChild(t2);
                    c3.appendChild(b1);
                    c3.appendChild(i1);
                }
                else {
                    if(elem["date"] == date){
                        let r = t.insertRow();
                        r.classList = "table-success"

                        let c1 = r.insertCell();
                        let t1 = document.createTextNode(elem["date"] + '  /  ' + elem["time"])

                        let c2 = r.insertCell();
                        let t2 = document.createTextNode(elem["type"] + '  /  ' + elem["description"])

                        let c3 = r.insertCell();
                        let b1 = document.createElement("input");
                        b1.type = "button"
                        b1.value = "Uradjeno"
                        b1.onclick = function() {scratchOff(this)}
                        let i1 = document.createElement("input");
                        i1.type = "submit"
                        i1.onclick = function() {deleteDuty(elem["id"])}
                        i1.value = "Obrisi"
                        
                        c1.appendChild(t1);
                        c2.appendChild(t2);
                        c3.appendChild(b1);
                        c3.appendChild(i1);
                    }
                }
            }
        })
    }

    function sortData(data){
        data.sort(function(a,b){
            
            let f = a.date + ' ' + a.time
            f = new Date(f)
            s = b.date + ' ' + b.time
            s = new Date(s)
            
            let results = f.getFullYear() < s.getFullYear() ? -1 : f.getFullYear() > s.getFullYear() ? 1 : 0;

            if (results == 0){
                results = f.getMonth() < s.getMonth() ? -1 : f.getMonth() > s.getMonth() ? 1 : 0;
            }
            if (results == 0){
                results = f.getDate() < s.getDate() ? -1 : f.getDate() > s.getDate() ? 1 : 0;
            }
            if (results == 0){
                results = f.getHours() < s.getHours() ? -1 : f.getHours() > s.getHours() ? 1 : 0;
            }
            if (results == 0){
                results = f.getMinutes() < s.getMinutes() ? -1 : f.getMinutes() > s.getMinutes() ? 1 : 0;
            }
            return results
        })
        return data
    }

    function insertDuty(elem){
        let date = elem.parentNode.querySelector('input[name=duty-date]').value
        let time = elem.parentNode.querySelector('input[name=duty-time]').value
        let ti = elem.parentNode.querySelector('select[name=duty-type]').value
        // console.log(ti)
        let desc = elem.parentNode.querySelector('textarea[name=duty-description]').value

        $.post("server.php", {
            'duty_date' : date,
            'duty_time' : time,
            'duty_ti' : ti,
            'duty_desc' : desc,
        },
        function(r){
            alert(r)
            display()
            elem.parentNode.reset()
        })
    }

    function insertType(elem){
        let name = elem.parentNode.querySelector('input[name=type-name]').value

        $.post("server.php", {
            'type_name' : name,
        },
        function(d){
            alert(d)
            elem.parentNode.reset()
            listType()
            displayType()
        })
    }

    function updateDuty(){
        let id = document.getElementById("duty-to-update").value
        if(id == -1){
            alert("Morate odabrati obavezu za izmenu!")
            return
        }
        $.get("json/duties.php", function(d){
            try{
                let data = JSON.parse(d)
                for(let i = 0; i < data.length; i++){
                    if(data[i]["id"] == id){
                        let elem = data[i]
                        document.getElementById("duty-id-update").value = elem["id"]
                        document.getElementById("duty-date-update").value = elem["date"]
                        document.getElementById("duty-time-update").value = elem["time"]
                        document.getElementById("duty-type-update").value = elem["type_id"]
                        document.getElementById("duty-description-update").value = elem["description"]
                    }
                }
            }
            catch(e){
                alert("Doslo je do greske")
            }
        })
    }

    function updateType(elem){
        let id = elem.parentNode.querySelector('select[name=type-id-update]').value
        let name = elem.parentNode.querySelector('input[name=type-name-update]').value
        if(id == -1 ){
            alert("Morate odabrati tip za izmenu!")
            return
        }
        $.post("server.php", {
            'update_type_id': id,
            'update_type_name' : name
        }, 
        function(){
            alert("Tip je izmenjen")
            location.reload();
        })
    }

    function deleteDuty(dutyId){
        $.post("server.php", {
                'delete_duty_id': dutyId,
            },
            function(){
                alert("Obaveza je obrisana")
                location.reload();
        });
    }

    function deleteType(typeId){
        $.post("server.php", {
                'delete_type_id': typeId,
            },
            function(){
                alert("Tip je obrisan")
                location.reload();
        });
    }

</script>
</html>