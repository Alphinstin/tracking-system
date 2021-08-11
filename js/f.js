function cW(id)
{
    var sw = $(window).width(); // get the window width
    const cw = 300;
    const sbw = 20;
    var present = function(element)
    {
        return element == id;
    }
    if(cws.some(present)){}
    else
    {
        if(sw > 320)
        {
            if($('.chatbox').length <= 0)
            {
                dfr = 30;
                // No chat windows open
                // Open a new Chat window
            }
            else
            {
                var ow = $('.chatbox').length;

                if(sw >= 700 && sw < 1000)
                {
                    if(ow == 0)
                    {
                        dfr = 30;
                        //Render chat window
                    }
                    else if(ow == 1)
                    {
                        dfr = 350;
                    }
                    else if(ow >= 2)
                    {
                        $('.cbox--holder').children('.chatbox:last').remove();  
                        dfr = 350;
                    }
                }
                else
                {
                    if(ow == 0)
                    {
                        dfr = 30;
                        //Render chat window
                    }
                    else if(ow == 1)
                    {
                        dfr = 350;
                    }
                    else if(ow == 2)
                    {
                        dfr = 670;
                    }
                    else if(ow >= 3)
                    {
                        dfr = 670
                        $('.cbox--holder').children('.chatbox:last').remove();  
                    }
                }

                
                //Chat Windows are open -done
                // Calculate the number open -done
                // Check if there is space -done
                // Calculate the coordinates for the next one
                // Add the next one;
                // if there close the last one
            }
            rc(dfr, id);
        }
    }
}
function rc(dfr,title)
{
    var chatbox = `<div class="chatbox" style = "right:${dfr}px" > 
                    <div class="chatbox__title" onclick = "hey(this)">
                    <h5><a href="#">${title}</a></h5>
                    <button class="chatbox__title__tray">
                            <span></span>
                    </button>
                    <button class="chatbox__title__close" onclick = "closeCW(this)">
                            <span>
                                <svg viewBox="0 0 12 12" width="12px" height="12px">
                                <line stroke="#FFFFFF" x1="11.75" y1="0.25" x2="0.25" y2="11.75"></line>
                                <line stroke="#FFFFFF" x1="11.75" y1="11.75" x2="0.25" y2="0.25"></line>
                                </svg>
                            </span>
                    </button>
                    </div>
                    <div class="chatbox__body scrollbar-deep-purple bordered-deep-purple thin   ">
                    
                    </div>
                    <!-- <form class="chatbox__credentials">
                    <div class="form-group">
                            <label for="inputName">Name:</label>
                            <input type="text" class="form-control" id="inputName" required>
                    </div>
                    <div class="form-group">
                            <label for="inputEmail">Email:</label>
                            <input type="email" class="form-control" id="inputEmail" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Enter Chat</button>
                    </form> -->
                    <textarea class="chatbox__message" placeholder="Write something interesting"></textarea>
                    <button type="button" onclick="gM(this)" class="btn btn-success"><i class = "fa fa-paper-plane"></i></button>
                </div>`;
    $('.cbox--holder').append(chatbox);
    // Add it in process list
    addCW(title);

}
function asyncRequest() {
    try {
        var request = new XMLHttpRequest(); // Non IE browsers;
    } catch (error) {
        try {
            request = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (error) {
            try {
                request = new ActiveXObject("MIcrosoft.XMLHTTP");
            } catch (error) {
                request = false;
            }
        }
    }
    
    return request;
}
function gO(num)
{
    //send an ajax request  which will get orders
    return new Promise((resolve, reject) => {
        var r = asyncRequest();
        if( r != false)
        {
            var e = false;
            const params = `l=${num}`;
            r.open("POST", "p.php", true);
            r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            r.onreadystatechange = function()
            {
                if(this.readyState == 4)
                {
                    if(this.status == 200)
                    {
                        if(this.responseText != null)
                        {
                            if(this.responseText != "No Orders!")
                            {
                                if(c['page_orders'] == "")
                                {
                                    c['page_orders'] = JSON.parse(this.responseText);
                                }
                                else c['current_orders'] = JSON.parse(this.responseText);
                            }
                        }
                        else e = true;
                    }
                }
            }
            r.send(params);
        }
        
        if(!e) resolve();
        else reject("Something Went Wrong!");
        
    });
}
function gN(id)
{
    // send an ajax request which will get notifications
    return new Promise((resolve, reject) => {
        var r = asyncRequest();
        if( r != false)
        {
            var e = false;
            const params = `id=${id}`;
            r.open("POST", "p.php", true);
            r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            r.onreadystatechange = function()
            {
                if(this.readyState == 4)
                {
                    if(this.status == 200)
                    {
                        if(this.responseText != null)
                        {
                            if(this.responseText != "No Notifications")
                            {  
                                if(c['page_notifications'] == "") c['page_notifications'] =JSON.parse(this.responseText);
                                else c['current_notifications'] = JSON.parse(this.responseText); 
                            }
                        }
                        else e = true;
                    }
                }
            }
            r.send(params);
        }
        if(!e) resolve();
        else reject("Something Went Wrong!");
    });
}
function rO()
{
    return new Promise((resolve, reject) => {
        let orders = "";
        let o = "";
        var er = false;
        if(c['current_orders'] == "")
        {
            console.log("1");
            // Format The result and send it to o
            // loop through
            c['current_orders'] = c['page_orders'];
            for (let index = 0; index < c['page_orders'].length; index++) {
                o += sRO(c['page_orders'][index], index);       
            }
            if(o == "")  $('#accordion').html(`<center>No Orders!</center>`);
            else $('#accordion').html(o);
        }
        else
        {
            let y = c['page_orders'];
            let z = c['current_orders'];
            let diff = false;
            if(y.length == z.length)
            {
                // Loop Through to check if they are the same
                for (let index = 0; index < y.length; index++) {
                    (z[index].description == y[index].description)? "" : diff = true;
                    (z[index].order_id == y[index].order_id)? "" : diff = true;
                    (y[index].issue_date == z[index].issue_date)? "": diff = true;
                    (y[index].issuer == z[index].issuer)? "":  diff = true;
                    (y[index].pdate1 == z[index].pdate1)? "": diff = true;
                    (y[index].pdate2 == z[index].pdate2)? "": diff = true;
                    (y[index].pdate3 == z[index].pdate3)?"": diff = true;
                    (y[index].stage == z[index].stage)?"" : diff = true;
                    (y[index].date == z[index].date)?"" : diff = true; 
                }
               if(!diff) resolve();
               else 
               {
                //    console.log(diff);
                   oD(y,z);
                   c['page_orders'] = c['current_orders']; 
               }
            }
            else
            {
                 c['page_orders']  = c['current_orders'] ;
                for (let index = 0; index < c['page_orders'].length; index++) {
                    o += sRO(c['page_orders'][index], index);       
                }
                $('#accordion').html(o);
            }
        }
      

        if(!er) resolve();
        else reject("Something went wrong!");
    });
}
function oD(old, newo)
{
    var diff = [];
    var d = [];
    var g = [];
       for (let index = 0; index < newo.length; index++) 
       {
          (old[index].description == newo[index].description)? "" : d.push("description");
          (old[index].issue_date == newo[index].issue_date)? "": d.push("issue_date");
          (old[index].issuer == newo[index].issuer)? "":  d.push("issuer");
          (old[index].order_id == newo[index].order_id)? "":  d.push("order_id");
          (old[index].pdate1 == newo[index].pdate1)? "": d.push("pdate1");
          (old[index].pdate2 == newo[index].pdate2)? "": d.push("pdate2");
          (old[index].pdate3 == newo[index].pdate3)?"": d.push("pdate3");
          (old[index].stage == newo[index].stage)? "":d.push("stage");
          (old[index].date == newo[index].stage)? "":d.push("date");
          diff[index] = d;
          d = [];
       }
    //   console.log(diff);
       for (let inde = 0; inde < diff.length; inde++)
       {
          if(diff[inde].length != 0)
          {
            var ol = document.getElementsByClassName('description');
            var ol1 = document.getElementsByClassName('issuer');
            var ol2 = document.getElementsByClassName('issued');
            var tp = document.getElementsByClassName('btn-circle');
           
            var tmp = diff[inde];
            // console.log(tmp);
            for (let index = 0; index < tmp.length; index++) {
                if(tmp[index] == "description") ol[inde].innerText = `Description: ${newo[inde].description}`;
                if(tmp[index] == "issuer") ol1[inde].innerText = `Issuer: ${newo[inde].issuer}`;
                if(tmp[index] == "issue_date") ol2[inde].innerText = `Issue Date: ${newo[inde].issue_date}` ;
                if(tmp[index] == "date") tp[inde].title = newo[index].date;
                if(tmp[index] == "pdate1")
                {
                    try {
                        var ol3 = document.getElementsByClassName('pdate1');
                        ol3[inde].innerText = `Stage 2: ${newo[inde].pdate1}`;
                    } catch (error) {

                        var cb = document.getElementsByClassName('card-body')
                        cb[inde].innerHTML += `<h6>Stage 2: ${newo[inde].pdate1}</h6>`;
                    }
                }
                if(tmp[index] == "pdate2")
                {
                    try {
                        var ol4 = document.getElementsByClassName('pdate2');
                        ol4[inde].innerText = `Stage 3: ${newo[inde].pdate2}`;
                    } catch (error) {

                        var cb = document.getElementsByClassName('card-body')
                        cb[inde].innerHTML += `<h6>Stage 3: ${newo[inde].pdate2}</h6>`;
                    }
                }
                if(tmp[index] == "pdate3")
                {
                    try 
                    {
                        var ol5 = document.getElementsByClassName('pdate3');
                        ol5[inde].innerText = `Stage 4: ${newo[inde].pdate3}`;
                    } catch (error) {

                        var cb = document.getElementsByClassName('card-body')
                        cb[inde].innerHTML += `<h6>Stage 4: ${newo[inde].pdate3}</h6>`;
                    }
                }
                if(tmp[index] == "stage")
                {
                    g[inde] = 1;
                    //cd[inde].innerHTML = sRO(c['current_orders'][inde],inde);
                }
            }
            // console.log(diff);
          }
       }
       let correct = document.getElementById('accordion');
       for (let index = 0; index < g.length; index++) {
           if(g[index] == 1)
           {
               correct.children[index].innerHTML = nsRO(c['current_orders'][index],index);
            //cd[index].innerHTML = sRO(c['current_orders'][index],index);
           }
       }
}
function addT(x)
{
    var d = "";
    if(x.stage == "3")   d += `<h6 class = "">Status: Closed Out</h6>`
    if(x.stage == "2")   d += `<h6 class = "" >Status: Submitted to Accounts</h6>`
    if(x.pdate1 != null) d += `<h6 class="pdate1">${x.pdate1}</h6>`;
    if(x.pdate2 != null) d += `<h6 class="pdate2">${x.pdate2}</h6>`;
    if(x.pdate3 != null) d += `<h6 class="pdate3">${x.pdate3}</h6>`;
    return d;
}
function sRO(x,index)
{

    var orders = `
    <div class="card orders">
        <div class="card-header" id="order${index}">
        <h5 class="mb-0">
        <button class="btn btn-link collapsed oname" data-toggle="collapse" data-target="#collapse${index}" aria-expanded="true" aria-controls="collapseOne">
            ${x.order_id}
        </button>
        <span class="pull-right">        <button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title = "Generate Report" onclick = "gR(this)"><i class="fa fa-gear tp"></i></button>
        <button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="${x.date}"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${x.order_id}')"><i class="fa fa-comment tp"></i></button></span>
        </h5>
        
        </div>

        <div id="collapse${index}" class="collapse" aria-labelledby="order${index}" data-parent="#accordion">
            <div class="card-body">
                <h6 class = "description">Description: ${x.description}</h6>
                <h6 class = "issuer">Issuer: ${x.issuer}</h6>
                <h6 class="issued">Issue Date: ${x.issue_date}</h6>
                ${addT(x)}
            </div>
        </div>
    </div>  
    `;
    if(department.trim() == "ACCOUNTS")
    {
        if(x.stage == "2")
        {
            orders = `
                    <div class="card orders">
                        <div class="card-header" id="order${index}">
                        <h5 class="mb-0">
                        <div class="checkbox" ><label><input type="checkbox" onchange= "uS()" class = "check" value = ${x.order_id}></label></div>
                       
                        <button class="btn btn-link collapsed oname" data-toggle="collapse" data-target="#collapse${index}" aria-expanded="true" aria-controls="collapseOne">
                            ${x.order_id}
                        </button>
                        <span class="pull-right">        <button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title = "Generate Report" onclick = "gR(this)"><i class="fa fa-gear tp"></i></button>
                        <button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="${x.date}" onclick = "gR(this)"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${x.order_id}')"><i class="fa fa-comment tp"></i></button></span>
                        </h5>
                        
                        </div>

                        <div id="collapse${index}" class="collapse" aria-labelledby="order${index}" data-parent="#accordion">
                            <div class="card-body">
                                <h6 class = "description">Description: ${x.description}</h6>
                                <h6 class = "issuer">Issuer: ${x.issuer}</h6>
                                <h6 class="issued">Issue Date: ${x.issue_date}</h6>
                                ${addT(x)}
                            </div>
                        </div>
                    </div>  
                    `;
        }
        
    }

    if(department.trim() == "SUPPLY CHAIN")
    {
        if(x.stage == "1")
        {
            orders = `
                    <div class="card orders">
                        <div class="card-header" id="order${index}">
                        <h5 class="mb-0">
                        <div class="checkbox" ><label><input type="checkbox" onchange= "uS()" class = "check" value = ${x.order_id}></label></div>
                        
                        <button class="btn btn-link collapsed oname" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            ${x.order_id}
                        </button>
                        <span class="pull-right">        <button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title = "Generate Report" onclick = "gR(this)"><i class="fa fa-gear tp"></i></button>
                        <button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="${x.date}"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${x.order_id}')"><i class="fa fa-comment tp"></i></button></span>
                        </h5>
                        
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="order${index}" data-parent="#accordion">
                            <div class="card-body">
                            <h6 class = "description">Description: ${x.description}</h6>
                            <h6 class = "issuer">Issuer: ${x.issuer}</h6>
                            <h6 class="issued">Issue Date: ${x.issue_date}</h6>
                            ${addT(x)}
                        </div>
                            </div>
                        </div>
                    </div>  
                    `;
        }
    }

    return orders;
}
function nsRO(x,index)
{
    var orders = `

        <div class="card-header" id="order${index}">
        <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse${index}" aria-expanded="true" aria-controls="collapseOne">
            ${x.order_id}
        </button>
        <span class="pull-right"><button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${x.order_id}')"><i class="fa fa-comment tp"></i></button></span>
        </h5>
        
        </div>

        <div id="collapse${index}" class="collapse" aria-labelledby="order${index}" data-parent="#accordion">
            <div class="card-body">
                <h6 class = "description">Description: ${x.description}</h6>
                <h6 class = "issuer">Issuer: ${x.issuer}</h6>
                <h6 class="issued">Issue Date: ${x.issue_date}</h6>
                ${addT(x)}
            </div>
        </div>
    `;
    if(department.trim() == "ACCOUNTS")
    {
        if(x.stage == "2")
        {
            orders = `
                        <div class="card-header" id="order${index}">
                        <h5 class="mb-0">
                        <div class="checkbox" ><label><input type="checkbox" onchange= "uS()" class = "check" value = ${x.order_id}></label></div>
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse${index}" aria-expanded="true" aria-controls="collapseOne">
                            ${x.order_id}
                        </button>
                        <span class="pull-right"><button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${x.order_id}')"><i class="fa fa-comment tp"></i></button></span>
                        </h5>
                        
                        </div>

                        <div id="collapse${index}" class="collapse" aria-labelledby="order${index}" data-parent="#accordion">
                            <div class="card-body">
                                <h6 class = "description">Description: ${x.description}</h6>
                                <h6 class = "issuer">Issuer: ${x.issuer}</h6>
                                <h6 class="issued">Issue Date: ${x.issue_date}</h6>
                                ${addT(x)}
                            </div>
                        </div>
                    `;
        }
        
    }

    if(department.trim() == "SUPPLY CHAIN")
    {
        if(x.stage == "1")
        {
            orders = `
                        <div class="card-header" id="order${index}">
                        <h5 class="mb-0">
                        <div class="checkbox" ><label><input type="checkbox" onchange= "uS()" class = "check" value = ${x.order_id}></label></div>
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            ${x.order_id}
                        </button>
                        <span class="pull-right"><button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${x.order_id}')"><i class="fa fa-comment tp"></i></button></span>
                        </h5>
                        
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="order${index}" data-parent="#accordion">
                            <div class="card-body">
                            <h6 class = "description">Description: ${x.description}</h6>
                            <h6 class = "issuer">Issuer: ${x.issuer}</h6>
                            <h6 class="issued">Issue Date: ${x.issue_date}</h6>
                            ${addT(x)}
                        </div>
                            </div>
                        </div>
                    `;
        }
    }

    return orders;
}
function sRN(x)
{
    var n = `
                <div class="alert alert-danger" role="alert" style = "margin-bottom:5px">
                   <p>${x.notification}  <span class = "nnn">${x.time}</span></p>
                </div>
            `;
    if(x.seen_value == "1") 
    {
        n = `
            <div class="alert alert-success" role="alert" style = "margin-bottom:5px">
                <p>${x.notification}
                <span class = "nnn">${x.time}</span>
                </p>
            </div>
        `;
    }
    return n;
}
function rN()
{
    return new Promise((resolve, reject) => {
        var o = "";
        var co = 0;
        var er = false;
        let y = c['page_notifications'];
        let z = c['current_notifications'];
        let yz = 0;
        if( z == "")
        {
            console.log("11");
            // Format The result and send it to o
          
            for (let index = 0; index < c['page_notifications'].length; index++) {
                o += sRN(c['page_notifications'][index]);       
            }
            for (let index = 0; index < c['page_notifications'].length; index++) {
                if(c['page_notifications'][index].seen_value == "0") co += 1;
            }
           
            if(co != 0)
            {
                if(document.getElementById('navbarDropdownMenuLink').innerHTML.trim() != "<i class='fa fa-bell'></i>")  document.getElementById('navbarDropdownMenuLink').innerHTML = ` <i class="fa fa-bell"></i><span class = "nn">${co}</span>`;
                else document.getElementById('navbarDropdownMenuLink').innerHTML = ` <i class="fa fa-bell"></i><span class = "nn">${co}</span>`;
            }
            if(document.getElementsByClassName('notification').length != 0)
            {
                document.getElementsByClassName('notification')[0].innerHTML = o;
                var notibox = document.getElementById('noti');
                notibox.scrollTop = 0;
    
            }
    
        }
        else
        {
            if(y.length == z.length)resolve();
            else
            {
                // console.log(y.length +" "+ z.length);
                for (let index = 0; index < z.length; index++) {
                    o += sRN(z[index]);       
                }
               
                c['page_notifications'] = c['current_notifications'];
                if(document.getElementsByClassName('fa-bell')[0].innerHTML.trim() != "")
                {
                    let b = document.getElementsByClassName('nn')[0].innerText;
                    yz = parseInt(b);
                }
                if(yz != 0)
                {
                    co = (z.length - y.length) + yz;
                }
                else
                {
                    co = z.length - y.length;
                }
                

                if(co != 0)
                {
                    if(document.getElementById('navbarDropdownMenuLink').innerHTML.trim() != "<i class='fa fa-bell'></i>")  document.getElementById('navbarDropdownMenuLink').innerHTML = ` <i class="fa fa-bell"></i><span class = "nn">${co}</span>`;
                    else document.getElementById('navbarDropdownMenuLink').innerHTML = ` <i class="fa fa-bell"></i><span class = "nn">${co}</span>`;
                }
                if(document.getElementsByClassName('notification').length != 0)
                {
                    document.getElementsByClassName('notification')[0].innerHTML = o;
                    var notibox = document.getElementById('noti');
                    notibox.scrollTop = 0;
        
                }

            }
        }

        //Set the values to the inner html;
       
        vv = o;
        if(!er) resolve();
        else reject("Something went wrong!");
    });
}
function wTRN()
{
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            var e = false;
            if(vv != "") 
            {
                if(document.getElementsByClassName('notification').length != 0)
                {
                    var notibox = document.getElementById('noti');
                    notibox.scrollTop = 0;
                    document.getElementsByClassName('notification')[0].innerHTML = vv;
                }
            }

            if(!e) resolve();
            else reject();
        }, 1000)
    });
}
function uN()
{
    return new Promise((resolve, reject) => {
        var seen = [];
        var notibox = document.getElementById('noti');
        var tt = document.getElementsByClassName('alert');
        var ne = "";
        let cd = c['prev'];

        if(cd.length > 0)
        {

            if(cd.length == tt.length)
            {
               
            }
            else
            {
                let diff = tt.length - cd.length;
                if(diff >= 3)
                {
                    $diff  = $diff - 3;
                    seen = [1,1,1];
                    for (let index = 3; index < diff; index++) {
                       seen[index] = 0;
                    }
                    seen = seen.concat(cd);
                }
                else
                {
                    seen = [];
                    for (let index = 0; index < diff; index++) {
                        seen[index] = 1;
                    }
                    seen = seen.concat(cd);
                }
            }
        }
        else
        {
            if(tt.length > 3)
            {
                seen = [1,1,1];
                for (let index = 3; index < tt.length; index++) {
                    seen[index] = 0;
                }
            }
            else
            {
                seen = [];
                for (let index = 0; index < tt.length; index++) {
                    seen[index] = 1;
                }
            }

        }
        c['prev'] = seen;
        notibox.addEventListener("scroll", ()=> {
           
            for(let i = 0; i < tt.length; i++)
            {
                lE(tt[i],notibox,seen,i);
            }

            for (let index = 0; index < tt.length; index++) {
                cE(tt[index],index);
           }
        });
        for (let index = 0; index < tt.length; index++) {
            cE(tt[index],index);
       }
        resolve();
    });
}
function lE(element, container,array,i)
{
    containerTop = container.scrollTop;
    containerBottom = containerTop + container.clientHeight;

    elementTop = element.offsetTop;
    elementBottom = elementTop + element.clientHeight;

    if(elementTop < containerTop && elementBottom < containerTop)           array[i] = 1;
    else if(elementTop >= containerTop && elementBottom <= containerBottom)  array[i] = 1;
    else
    {
        if(array[i] === 1) array[i] = 1;
        else array[i] = 0;
    }
}
function cE(element,index)
{

    var h = m = "";
    let y = c['current_notifications'];
    let z = c['page_notifications'];
    let t = "";
    if(c['page_notifications'][index] != undefined)
    {
        t = c['page_notifications'][index].id;
    }
    else 
    {
        t = c['page_notifications'][index-1].id;
    }
    
    if(element.classList.contains('alert-danger'))
    {
        if(c['prev'][index] == 1)
        {
            if(q[2] == undefined)
            {
                q[2] = {};
                q[2][t] ="1";
            }
            else 
            {
                q[2][t] ="1";
            }
            element.classList.remove('alert-danger');
            element.classList.add('alert-success');
           
            if(y.length == z.length)
            {
                c['current_notifications'][index].seen_value = "1";

            }
            // var h = c['page_notifications'][index].id;
            // var m =  c['page_notifications'][index].seen_value;
        }
        var cdg = document.getElementsByClassName('alert-danger').length;
        if(cdg > 0) document.getElementById('navbarDropdownMenuLink').innerHTML = ` <i class="fa fa-bell"></i><span class = "nn">${cdg}</span>`
        else  document.getElementById('navbarDropdownMenuLink').innerHTML = ` <i class="fa fa-bell"></i>`;
    
    }
    else 
    {

    }
    // Recalculate number

}
function sN()
{
   wTRN().then(uN).then(
       () => {
           return new Promise((resolve, reject) => {
               resolve();
           });
       }
    ).catch(err => console.log(err));
}
function addCW(id)
{
   switch (cws.length) {
       case 0:
            cws.push(id);
        break;
        case 1:
            cws.push(id);
        break;
        case 2:
            cws.push(id);
        break;
        case 3:
            cws[2] = id;
        break;
       default:
           cws[2] = id;
           break;
   }
   if(q.length == 0) q.push(cws);
   else
   {
      q.pop();
      q.push(cws);
   }
}
function gC(x)
{
  return new Promise((resolve, reject) => {
      switch(x.length)
      {
          case 1:
            t1 = setTimeout(() => {
                var e = false;
                var r = asyncRequest();
                if( r != false)
                {
                    var e = false;
                    const params = `cid=${x[0]}`;
                    r.open("POST", "p.php", true);
                    r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                    r.onreadystatechange = function()
                    {
                        if(this.readyState == 4)
                        {
                            if(this.status == 200)
                            {
                                if(this.responseText != null)
                                {
                                    if(c['chat-box-1'] == "")c['chat-box-1'] = JSON.parse(this.responseText);
                                    else c['nchat-box-1'] = JSON.parse(this.responseText); 
                                }
                                else e = true;
                            }
                        }
                    }
                        r.send(params);
                }
                else reject("Something Went Wrong!");
                if(!e) resolve();
                else reject("Something Went Wrong!");
            },500);        
          break;
          case 2:
            t1 = setTimeout(() => {
                var e = false;
                var r = asyncRequest();
                var r1 = asyncRequest();
                if( r != false)
                {
                    var e = false;
                    const params = `cid=${x[0]}`;
                    r.open("POST", "p.php", true);
                    r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
                    r.onreadystatechange = function()
                    {
                        if(this.readyState == 4)
                        {
                            if(this.status == 200)
                            {
                                if(this.responseText != null)
                                {
                                    if(c['chat-box-1'] == "") c['chat-box-1'] = JSON.parse(this.responseText);
                                    else c['n-chat-box-1'] = JSON.parse(this.responseText); 
                                }
                                else e = true;
                            }
                        }
                    }
                    r.send(params);
                }
                else reject("There was an error retrieving messages!");
                if( r1 != false)
                {        
                    const params = `cid=${x[1]}`;
                    r1.open("POST", "p.php", true);
                    r1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    r1.onreadystatechange = function()
                    {
                        if(this.readyState == 4)
                        {
                            if(this.status == 200)
                            {
                                if(this.responseText != null)
                                {
                                    if(c['chat-box-2'] == "") c['chat-box-2'] =JSON.parse(this.responseText);
                                    else c['n-chat-box-2'] = JSON.parse(this.responseText); 
                                }
                                else e = true;
                            }
                        }
                    }
                    r1.send(params);
                }
                else  reject("There was an error retrieving messages!");
                if(!e) resolve();
                else reject("Something Went Wrong!");
            },500);
          break;
          case 3:
             t1 = setTimeout(() => {
                var e = false;
                var r = asyncRequest();
                var r1 = asyncRequest();
                var r2 = asyncRequest();
                if( r != false)
                {
                    var e = false;
                    const params = `cid=${x[0]}`;
                    r.open("POST", "p.php", true);
                    r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                   
                    r.onreadystatechange = function()
                    {
                        if(this.readyState == 4)
                        {
                            if(this.status == 200)
                            {
                                if(this.responseText != null)
                                {
                                    if(c['chat-box-1'] == "") c['chat-box-1'] = JSON.parse(this.responseText);
                                    else c['n-chat-box-1'] = JSON.parse(this.responseText); 
                                }
                                else e = true;
                            }
                        }
                    }
                    r.send(params);
                }
                else reject("There was an error retrieving messages!");
                if( r1 != false)
                {        
                    const params = `cid=${x[1]}`;
                    r1.open("POST", "p.php", true);
                    r1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                   
                    r1.onreadystatechange = function()
                    {
                        if(this.readyState == 4)
                        {
                            if(this.status == 200)
                            {
                                if(this.responseText != null)
                                {
                                    if(c['chat-box-2'] == "") c['chat-box-2'] = JSON.parse(this.responseText);
                                    else c['n-chat-box-2'] = JSON.parse(this.responseText); 
                                }
                                else e = true;
                            }
                        }
                    }
                    r1.send(params);
                }
                else  reject("There was an error retrieving messages!");
                if( r2 != false)
                {
                    var e = false;
                    const params = `cid=${x[2]}`;
                    r2.open("POST", "p.php", true);
                    r2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                 
    
                    r2.onreadystatechange = function()
                    {
                        if(this.readyState == 4)
                        {
                            if(this.status == 200)
                            {
                                if(this.responseText != null)
                                {
                                    if(c['chat-box-3'] == "") c['chat-box-3'] = JSON.parse(this.responseText);
                                    else c['n-chat-box-3'] = JSON.parse(this.responseText); 
                                }
                                else e = true;
                            }
                        }
                    }
                    r2.send(params);
                }
                else reject("There was an error retrieving messages!");
                if(!e) resolve();
                else reject("Something Went Wrong!");
            },500);
          break;
      }
  });
}
function sRC(x)
{
    $chatm  = ` <div class="chatbox__body__message chatbox__body__message--left">
                    <img src="" alt="">
                    <p>
                        <span class = "ln">${x.employee}</span>
                        ${x.post}
                        <span class = "rt">${x.date}</span>
                    </p>
                </div>`;
    if(x.employee == employee)
    {
        $chatm = ` <div class="chatbox__body__message chatbox__body__message--right">
                        <img src="" alt="">
                        <p>
                            <span class = "rt">${x.employee}</span>
                            ${x.post}
                            <span class = "lt">${x.date}</span>
                        </p>
                    </div>`;
    }
    return $chatm;
}
function rC()
{
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            var e = false;
            var o = "";
            let y = c['chat-box-1'];
            let z = c['n-chat-box-1'];
            let y1 = c['chat-box-2'];
            let z1 = c['n-chat-box-2'];
            let y2 = c['chat-box-3'];
            let z2 = c['n-chat-box-3'];
            var ci = "";
            

            if(z != "")
            {
               if(y.length == z.length)
               {

               }
               else
               {
                   for (let index = 0; index < y.length; index++) {
                      o += sRC(y[index]);
                   }
                   if(q[0] != undefined)
                   {
                       // loop through
                       for (let index = 0; index < y.length; index++) {
                           ci =  y[index].chat_id;
                           break;                  
                       }
                       const g = (element) => 
                       {
                           return element == ci;
                       }
                       if(q[0].some(g)) 
                       {
                           var i = "";
                           for (let index = 0; index < q[0].length; index++) {
                               if(c['chat-box-1'][0].chat_id == q[0][index])
                               {
                                   i = index;
                                   break;
                               }
                           }
                           document.getElementsByClassName('chatbox__body')[i].innerHTML = o;
                           o = "";
                           ci = "";
                           c['chat-box-1'] = c['n-chat-box-1'];
                       }
                   }
               }
            }
            else
            {
                // 
                if(y.length > 0)
                {
                    
                    for (let index = 0; index < y.length; index++) {
                        o += sRC(y[index]);
                     }
                    if(q[0] != undefined)
                    {
                        for (let index = 0; index < y.length; index++) {
                            ci =  y[index].chat_id;
                            break;                  
                        }
                        const g = (element) => 
                        {
                            return element == ci;
                        }
                        if(q[0].some(g)) 
                        {
                            var i = "";
                            for (let index = 0; index < q[0].length; index++) {
                                if(c['chat-box-1'][0].chat_id == q[0][index])
                                {
                                    i = index;
                                    break;
                                }
                            }
                            document.getElementsByClassName('chatbox__body')[i].innerHTML = o;
                            o = "";
                            c['chat-box-1'] = c['n-chat-box-1'];
                        }
                    }
                }
            }
            if(z1 != "")
            {
               if(y1.length == z1.length)
               {

               }
               else
               {
                   for (let index = 0; index < y1.length; index++) {
                      o += sRC(y1[index]);
                   }
                   if(q[0] != undefined)
                   {
                        for (let index = 0; index < y.length; index++) {
                            ci =  y1[index].chat_id;
                            break;                  
                        }
                        const g = (element) => 
                        {
                            return element == ci;
                        }
                       if(q[0].some(g)) 
                       {
                           var i = "";
                           for (let index = 0; index < q[0].length; index++) {
                               if(c['chat-box-2'][0].chat_id == q[0][index])
                               {
                                   i = index;
                                   break;
                               }
                           }
                           document.getElementsByClassName('chatbox__body1')[i].innerHTML = o;
                           o = "";
                           ci = "";
                           c['chat-box-2'] = c['n-chat-box-2'];
                       }
                   }
               }
            }
            else
            {
                // 
                if(y1.length > 0)
                {
                    
                    for (let index = 0; index < y1.length; index++) {
                        o += sRC(y1[index]);
                     }
                    if(q[0] != undefined)
                    {
                        for (let index = 0; index < y.length; index++) {
                            ci =  y[index].chat_id;
                            break;                  
                        }
                        const g = (element) => 
                        {
                            return element == ci;
                        }
                        if(q[0].some(g)) 
                        {
                            var i = "";
                            for (let index = 0; index < q[0].length; index++) {
                                if(c['chat-box-2'][0].chat_id == q[0][index])
                                {
                                    i = index;
                                    break;
                                }
                            }
                            document.getElementsByClassName('chatbox__body')[i].innerHTML = o;
                            o = "";
                            ci = "";
                            c['chat-box-2'] = c['n-chat-box-2'];
                        }
                    }
                }
            }
            if(z2 != "")
            {
               if(y2.length == z2.length)
               {

               }
               else
               {
                   for (let index = 0; index < y2.length; index++) {
                      o += sRC(y2[index]);
                   }
                   if(q[0] != undefined)
                   {
                        for (let index = 0; index < y.length; index++) {
                            ci =  y2[index].chat_id;
                            break;                  
                        }
                        const g = (element) => 
                        {
                            return element == ci;
                        }
                       if(q[0].some(g)) 
                       {
                           var i = "";
                           for (let index = 0; index < q[0].length; index++) {
                               if(c['chat-box-3'][0].chat_id == q[0][index])
                               {
                                   i = index;
                                   break;
                               }
                           }
                           document.getElementsByClassName('chatbox__body')[i].innerHTML = o;
                           o = "";
                           ci = "";
                           c['chat-box-3'] = c['n-chat-box-3'];
                       }
                   }
               }
            }
            else
            {
                // 
                if(y2.length > 0)
                {
                    
                    for (let index = 0; index < y2.length; index++) {
                        o += sRC(y2[index]);
                     }
                    if(q[0] != undefined)
                    {
                        for (let index = 0; index < y.length; index++) {
                            ci =  y2[index].chat_id;
                            break;                  
                        }
                        const g = (element) => 
                        {
                            return element == ci;
                        }
                        if(q[0].some(g)) 
                        {
                            var i = "";
                            for (let index = 0; index < q[0].length; index++) {
                                if(c['chat-box-3'][0].chat_id == q[0][index])
                                {
                                    i = index;
                                    break;
                                }
                            }
                            document.getElementsByClassName('chatbox__body')[i].innerHTML = o;
                            o = "";
                            ci = "";
                            c['chat-box-3'] = c['n-chat-box-3'];
                        }
                    }
                }
            }
            if(!e) resolve();
            else reject();
        },1000);
    });   
}
function hey(x){
    x.parentNode.classList.toggle("chatbox--tray");
}
function closeCW(x)
{
    var title = x.parentNode.firstChild.nextElementSibling.firstChild.innerText;
    var id = "";
    cws.forEach((element,index) => {
        if(title === element) {id = index;}
    });
    cws.splice(id, 1);
    $('.chatbox').eq(id).remove();
    align(id);
}
function align(x)
{
    if(x == 0)
    {
        if($('.chatbox').length == 1) $('.chatbox').eq(0).attr("style", "right:30px");
        else if($('.chatbox').length == 2)
        {
            $('.chatbox').eq(0).attr("style", "right:30px");
            $('.chatbox').eq(1).attr("style", "right:350px");
        }
    }
    else if(x == 1)
    {
        if($('.chatbox').length == 2)$('.chatbox').eq(1).attr("style", "right:350px");
    }
}
function searchs()
{
    return new Promise((resolve, reject) => {
        $('#search_results').dropdown('toggle');
        var x = $('#search').val();
        var sr = "";
        if(x.trim() != "")
        {
            var r1 = new asyncRequest();
            if(r1 != false)
            {
                const params = `s=${x}`;
                r1.open("POST", "p.php", true);
                r1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                r1.onreadystatechange = function()
                {
                    if(this.readyState == 4)
                    {
                        if(this.status == 200)
                        {
                            c['search_results']=JSON.parse(this.responseText);
                            for (let index = 0; index < c['search_results'].length; index++) {
                                sr += rSR(c['search_results'][index], index);                                
                            }
                            document.getElementById('results').innerHTML = sr;
                            $('#search_results').dropdown('toggle');
                            resolve();
                        }
                    }
                };

                r1.send(params);
            }
            else reject("Something Went Wrong!");
        }
        else{
            $('#search_results').dropdown('dispose');

            document.getElementById('results').innerHTML = "";
        }
    });
}
function rSR(x,index)
{
    // Takes in an object
    // returns a modal  button
    var result  = `
                <div class="alert alert-success" role="alert" style = "margin-bottom:5px">
                   <a href = "javascript:void(0)" onclick = "heyy(this)" class = "am">
                        <h6>${x.order_id}</h6>
                        <h6 style="font-size: 13px;">${x.description}</h6>
                        <h6 style="font-size: 13px;">${x.issuer}</h6>

                   </a>
                </div>
    `;
    return result;
}
function logout()
{
   var c = confirm("Are you sure!");
   if(c)
   {
     
      var d = document.createElement("script");
      d.src= "l.php";
      document.body.appendChild(d);
      setTimeout(() => {
         location.href = 'login.php?fl=shdfhzlfhafkaufijaiujhauifuajkfhiaujfhuaijkfs';
      }, 1000);
   }
   else return false;
   
}
function gM(field) {
    var c = {};
    var d = "";
    if((field.previousElementSibling.value).trim() != "")
    {
        c['employee']    = employee;
        c['post']        = field.previousElementSibling.value;
        c['time']        = new Date();
        c['chat_id']     = field.parentNode.firstElementChild.innerText;
        cms.push(c);
        q[1] = cms;
        field.previousElementSibling.value = "";
        cms = [];
    }
}
function iM(messages)
{
    return new Promise((resolve, reject) => {
        var r1 = new asyncRequest();
        var m = JSON.stringify(q[1]);
        console.log(m);
        if(r1 != false)
        {
            
            const params = `messages=${m}`;
            r1.open("POST", "p.php", true);
            r1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          
            r1.onreadystatechange = function()
            {
                if(this.readyState == 4)
                {
                    if(this.status == 200)
                    {
                        console.log(this.responseText);
                        resolve();
                    }
                }
            };

            r1.send(params);
        }
        else reject("Something Went Wrong!");
    });
}
function unsee()
{
    return new Promise((resolve, reject) => {
        var r1 = new asyncRequest();
        let m = JSON.stringify(q[2]);
        if(r1 != false)
        {

            const params = `unsee=${m}`;
           
            r1.open("POST", "p.php", true);
            r1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            r1.onreadystatechange = function()
            {
                if(this.readyState == 4)
                {
                    if(this.status == 200)
                    {
                        console.log(this.responseText);
                        resolve();
                    }
                }
            };

            r1.send(params);
        }
        else reject("Something Went Wrong!");
    });
}
function heyy(x)
{
    let inde = "";
    c['search_results'].forEach((element,index) => {
        if(element.order_id === x.firstElementChild.innerText) inde = index;
    });
    // After getting the index you then get the
    rM(inde);
    $('#myLargeModalLabel').modal('show');
}
function rM(x)
{
    let md = c['search_results'][x];
    let modal  = `
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id = "myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 350px;padding: 20px;">
                <div class="modal-header">
                    <h3 class="modal-title" style="width: 100%;">
                        ${md.order_id}
                        <span class="pull-right"><button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fa fa-clock-o tp"></i></button><button type = button class = "btn btn-success btn-circle" onclick = "cW('${md.order_id}')"><i class="fa fa-comment tp"></i></button></span>
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   
                    <div>
                        <h6>Description: ${md.description}</h6>
                        <h6>Issuer:${md.issuer}</h6>
                        <h6>Issue Date${md.issue_date}</h6>
                        ${addT(md)}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>      
    `;
    let modalholder = document.getElementById('modal-holder');
    if(modalholder.innerHTML.trim() != "") 
    {
        console.log("There is already a modal in place");
        modalholder.innerHTML = modal;
    }
    else
    {
        modalholder.innerHTML = modal;
    }
}
function rAMO(x)
{
    var option = "";
    for (let index = 0; index < x.length; index++) {
        option += `
                    <div class="alert alert-success" role="alert" style = "margin-bottom:5px">
                        <p style="margin-bottom: 0px;">
                        <span><input type = "checkbox" class = "final" value = "${x[index]}" checked></span>
                        ${x[index]}
                        </p>
                    </div>
                    `;
    }
    return option;
}
function rAM(x)
{
    let modal = `
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" a   ria-labelledby="myLargeModalLabel" aria-hidden="true" id = "myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 350px;padding: 20px;">
                <div class="modal-header">
                    <h3 class="modal-title" style="width: 100%;">
                      Proceed to Approve
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ${rAMO(x)}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick = "fOA()">Approve</button>
                </div>
            </div>
        </div>
    </div>      
    `;
    return modal;
}
function uS()
{
    let check = document.getElementsByClassName('check');
    var coun = 0;
    const single = document.getElementById('single');
    const multiple = document.getElementById('multiple');
    //Loop through to know how many are checked
    for(let i = 0; i <check.length; i++)
    {
        if(check[i].checked) coun += 1;
    }
    if(coun > 1)
    {
        if(multiple.classList.contains('disabled')) 
        {
            multiple.classList.remove('disabled');
        }
        if(!single.classList.contains('disabled')) 
        {
            single.classList.add('disabled');
        }
    }
    else if(coun == 1)
    {
        if(single.classList.contains('disabled')) 
        {
            single.classList.remove('disabled');
        }
    }
    if(coun == 0)
    {
        if(!single.classList.contains('disabled')) 
        {
            single.classList.add('disabled');
        }
        if(!multiple.classList.contains('disabled')) 
        {
            multiple.classList.add('disabled');
        }
    }
     
}
function approve()
{
    // loop through to find out how may are checked
    let check = document.getElementsByClassName('check');
    let tba = [];
    for(let i = 0; i <check.length; i++)
    {
        if(check[i].checked) tba.push(check[i].value);
    }
    // render modal
    document.getElementById('modal-holder').innerHTML = rAM(tba);
    $('#myLargeModalLabel').modal('show'); 
}
function fOA()
{
    let check = document.getElementsByClassName('final');
    if(check.length == 0) alert("Please Select the boxes for Approval or close");
    else
    {
        // Get the ones approved
        for (let index = 0; index < check.length; index++) {
            if(check[index].checked)
            {
                if(q[3] === undefined)
                {
                    q[3] = {};
                    q[3][index] = check[index].value;
                }
                else q[3][index]= check[index].value;
                
            }         
        }
    }
    $('#myLargeModalLabel').modal('hide'); 
}
function sA()
{
    return new Promise((resolve, reject) => {
        let r = asyncRequest();
        let er = false;
        if(r != false)
        {
            let ar = JSON.stringify(q[3]);
            const params = `approve=${ar}`;
            console.log(params);
            r.open("POST", "p.php", true);
            r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            r.onreadystatechange = () => {
                if(this.readyState == 4)
                {
                    
                    if(this.status == 200)
                    {
                        console.log(this.responseText);
                        let c = document.getElementsByClassName('sa');
                        if(!c[0].classList.contains('disabled')) c[0].classList.add('disabled');
                        if(!c[1].classList.contains('disabled')) c[1].classList.add('disabled');
                    }
                }
            }

            r.send(params);
        }
        else reject("Something Went Wrong!");

        if(!er) resolve();
        else reject();
    });
}
function gR(id)
{

        // Get the element id then
        let p = id.offsetParent;
        let pf = p.firstElementChild;
        let pff = pf.firstElementChild;
        let pffc = pff.children[0].innerText;
        if(pffc == "") pffc = pff.children[1].innerText;
    console.log(pffc);
    gRC(pffc)
    .then(result => 
        {
            aM(result[0], result[1]);
        })
    .catch(err => console.log(err));
}
function gRC(ar)
{
    return new Promise((resolve, reject) => {
        // When I get the value 
        // sending Async Request
        let r9 = asyncRequest();
        let er = false;
        let result = "";
        if(r9 != false)
        {
            const params = `cid=${ar}`;
            r9.open("POST", "p.php", true);
            r9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            r9.onreadystatechange = () => {
                if(r9.readyState == 4)
                {
                    
                    if(r9.status == 200)
                    {
                        result = JSON.parse(r9.responseText);
                        resolve([ar, result]);
                    }
                }
            }

            r9.send(params);
        }
        else reject("Something Went Wrong!");

    });
}
function aM(element,x)
{
    // x is the JSON decoded form of the thing
    return new Promise((resolve, reject) => {
        let f = document.getElementsByClassName('oname');
        let pages = "";
       var i = 0;
        for (let index = 0; index < c['page_orders'].length; index++) {
            if(c['page_orders'][index].order_id == element) i = index;
        }
        // Get The properties
        let v = c['page_orders'][i];
        let page0 = `<div class = "container"><div id = "page0">
                        <h3 align = "center">Drillog Petro Dynamics Limited</h3>
                        <center><h4>709 Reports</h4></center>
                            <h6>Reference Number: ${v.order_id}</h6>
                            <h6>Issued By: ${v.issuer}</h6>
                            <h6> Issue Date: ${v.issue_date} </h6>
                            <h6> Description: ${v.description} </h6>
                        </div></div>`;
        pages = `<div class = "container"> ${cT(x)} </div>`;
        
        let mh = document.getElementById('modal-holder');
        mh.innerHTML = `
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id = "myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="padding: 20px;">
                    <div class="modal-header">
                        <h3 class="modal-title" style="width: 100%;">
                            ${element} Report
                        </h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       ${page0 + pages}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick = "gPDF()">Generate</button>
                    </div>
                </div>
            </div>
        </div>      `;
        $('#myLargeModalLabel').modal('show');
        resolve();
    });
}
function cT(x)
{
    let table = `<table class = "table report" id = "table0">
                    <thead class="thead-dark">
                    <tr >
                        <th scope="col">Employee</th>
                        <th scope="col">Post</th>
                        <th scope="col">Time</th>
                    </tr>
                    </thead>
                    <tbody>
                        ${rMC(x,0)}
                    </tbody>    
               </table>`
    if(x.length > 20)
    {
        table = "";
        let pages = parseInt(x.length / 20);

        pages = Math.ceil(pages);
        pages += 1;
        console.log(pages);
        var initial = 0,final = 19;
        var tmp = "";
        for (let index = 0; index < pages; index++) {
            if(index > 0)
            {
                initial = index * 20;
                final = initial + 9;
                if(x.length < final + 1) final = x.length -1;
            }
            table += `<table class = "table report" id = "table${index}">
                            <thead class="thead-dark">
                            <tr >
                                <th scope="col">Employee</th>
                                <th scope="col">Post</th>
                                <th scope="col">Time</th>
                            </tr>
                            </thead>
                            <tbody>
                                ${nT1(x,initial, final)}
                            </tbody>    
                    </table>`;
        }
    }
  return table;
}
function rMC(x)
{
    let tb = "";
    
    if(x.length > 0)
    {
        for(let i = 0; i < x.length; i++)
        {
            tb += `<tr class = "table-success">
                        <td>${x[i].employee}</td>
                        <td>${x[i].post}</td>
                        <td>${x[i].date}</td>
                    </tr>`;
        }
    }
    else 
    {
        tb = `<tr class = "table-success" ><td colspan = "3"><center>No Converstaions on This Chat</center></td></tr>`;
    }
    return  tb;
}
function nT1(x, initial, final)
{
    var  rows = "";
    for (let index = initial; index < final + 1; index++) {
        rows += `<tr class = "table-success">
        <td>${x[index].employee}</td>
        <td>${x[index].post}</td>
        <td>${x[index].date}</td>
        </tr>`
    }
    return rows;
}
function nT(x)
{
    console.log(x);
    let values = `<tr class = "table-success">
    <td>${x.employee}</td>
    <td>${x.post}</td>
    <td>${x.date}</td>
    </tr>`;
    return values;
}
function gPDF()
{
    // FIrst get the number of pages
    let description = document.getElementById('page0');
    var tableNum = document.getElementsByClassName('report');
    // Loop Through
    // Create New PDF DOcument OBject
    var report = new jsPDF();
    var pages = [];
    var tables = [];
    // Capture the Description Page
    function start(){
        return new Promise((resolve, reject) => {
            let g = false;
            for (let index = 0; index < tableNum.length; index++) {
                html2canvas(tableNum[index],{width:"745", height:"1042"})
                .then(canvas => {
                    let desc = canvas.toDataURL("image/png");
                    tables[index] = desc;
                });
                if(index == tableNum.length - 1) g = true;
            }
            if(g) resolve();
        });
    }
    start()
    .then(() => {
        html2canvas(description).then((canvas) => {
            let desc = canvas.toDataURL("image/png");
            pages[0] = desc;
            let r = pages.concat(tables);
            if(r.length > 0) {                
                for(let i = 0; i < r.length; i++)
                {
                    if(i == 0)
                    {
                        report.addImage(r[i],'JPEG', 13,20);
                    }
                    else 
                    {
                        report.addPage();
                        report.addImage(r[i],'JPEG', 13,20);
                    }
                }
                report.save('report.pdf');
                }
             else
             {
        
             }
        });
    })
    .catch(err => console.log(err));
}
vv = "t";
pl = "";
q = [];
c = {
    'page_orders':'',
    'current_orders': '',
    'page_notifications':'',
    'current_notifications':'',
    'chat-box-1':'',
    'n-chat-box-1':'',
    'chat-box-2':'',
    'n-chat-box-2':'',
    'chat-box-3':'',
    'n-chat-box-3':'',
    'prev':[],
    'search_results':''
};
cws = [];
cms = [];
t1 = t2 = t3 = "";
num = 25;
function ty()
{
    return new Promise((resolve, reject) => {
        if(q[0] == undefined)
        {
            q.pop();
        }
        resolve();
    });
}

function tz()
{
    return new Promise((resolve, reject) => {
        if(q[0] == undefined)
        {
            q.pop();
        }
        resolve();
    });
}

function tz()
{
    return new Promise((resolve, reject) => {
        if(q[1] == undefined)
        {
            q.pop();
        }
        resolve();
    });
}
$(document).ready(function()
{      
    var h = setInterval(
        () => {
            if(q.length == 0)
            {
                gO(num)
                .then(result => gN(department))
                .then(rO)
                .then(rN)
                .catch(err => console.log(err));
            }
            else if(q.length == 1)
            {
               ty()
               .then(()=> {
                if(q[0] != undefined) gC(q[0]);
               })
                .then(rC)
                .then(result => gO(num))
                .then(result => gN(employee))
                .then(rO)
                .then(rN)
                .catch(err => console.log(err));                   
            }
            else if(q.length == 2)
            {
                tz()
                .then(() => {
                    if(q[1] != undefined) iM(q[1]);
                })
                 .then(() => {
                     q.pop();
                 })
                .then(result => {
                    if(q[0] != undefined) gC(q[0]);
                })
                .then(rC)
                .then(result => gO(num))
                .then(result => gN(employee))
                .then(rO)
                .then(rN)
                .catch(err => console.log(err));     
            }
            else if(q.length == 3)
             {
                 unsee()
                 .then(() => {
                  return new Promise((resolve, reject) => {
                            q.pop();
                            // console.log("here");
                            resolve();
                        });
                    })
                .then(result => gO(num))
                .then(result => gN(employee))
                .then(rO)
                .then(rN)
                .catch(err => console.log(err));  
             }
             else if(q.length == 4)
             {
                sA()
                .then(() => {
                    q.pop();
                })
                .then(() => {
                    if(q[1] == undefined) q.pop();
                    if(q[0] == undefined) q.pop();
                })
                .then(result => gO(num))
                .then(result => gN(employee))
                .then(rO)
                .then(rN)
                .catch(err => console.log(err));  
             }
        }
    ,1000);
}
);

