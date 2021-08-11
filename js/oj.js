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
                        <span class="pull-right"><button type="button" class="btn btn-secondary btn-circle" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fa fa-clock-o tp"></i></button></span>
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
                        <span><input type = "checkbox" class = "final" value = "${x[index]}"></span>
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
function addT(x)
{
    var d = "";
    if(x.stage == "3")   d += `<h6 class = "">Closed Out</h6>`
    if(x.pdate1 != null) d += `<h6 class="pdate1">${x.pdate1}</h6>`;
    if(x.pdate2 != null) d += `<h6 class="pdate2">${x.pdate2}</h6>`;
    if(x.pdate3 != null) d += `<h6 class="pdate3">${x.pdate3}</h6>`;
    return d;
}
$(document).ready(
    () => {
        setInterval( 
            () => {
                gN()
                .then(rN)
                .then(() => {
                    if(q[2] != undefined) unsee();
                })
                .then(() => {
                    if(q[2] != undefined) q.pop();
                    if(q.length != 0) q.pop();
                })
                .catch(err => console.log(err));
            }
            ,1000);
        
    }
);