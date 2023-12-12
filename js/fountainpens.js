let map;
let kmlLayer;
let loc;
let places;

const apiKey = "AIzaSyDlb1zBxH5fDeTfg8njkF4_KcURiGxL29A";

// Google Maps API 
(g=>{var v="weekly",h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;
b=b[c]||(b[c]={});
var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));
e.set("libraries",[...r]+"");
for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);
e.set("callback",c+".maps."+q);
a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;
a.onerror=()=>h=n(Error(p+" could not load."));
a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));
d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
  key: apiKey,
});

// updates copyright footer, and sets active nav link
function onLoad() {
  document.getElementById("year").innerHTML = new Date().getFullYear();

// save title of the current page
  var title = document.getElementsByTagName("title")[0].innerHTML;

// collect all the nav ul items
  var navLinks = document.getElementsByTagName("nav")[0].getElementsByTagName('li');

// go through each nav item and set class active based on title match
  for(i=0;i<navLinks.length;i++){
    var evalText = navLinks[i].innerText.replace("&", "&amp;")
    if(title.includes(evalText)) navLinks[i].firstChild.classList.add("active")
  }
}




// build museum map with kml layer and multimedia
async function initMuseumMap() {
  const { Map } = await google.maps.importLibrary("maps");

  map = new Map(document.getElementById("historyMap"), {
    center: { lat: 35.397, lng:-105.644 },
    zoom: 2,
  });

  google.maps.event.trigger(map, 'resize');
  map.setZoom( map.getZoom() );

  var src = 'places-draft.kml';
  kmlLayer = new google.maps.KmlLayer(src, {
    suppressInfoWindows: false,
    preserveViewport: false,
    map: map
  });

  kmlLayer.addListener('click', function(event) {
    var content = event.featureData.infoWindowHtml;
  });
}

/* not used- replaced with addInfoWindowMarker */
function addPlainMarker(map, site){
    latLng = new google.maps.LatLng(site.Lat, site.Lng);
    marker = new google.maps.Marker(
    {
      position: latLng, 
      title: `${site.Name}, ${site['End Date']}`,
      content: `Get more info at ${site.Website}`
    }
    );

    marker.setMap(map);
}

async function addInfoWindowMarker(map, site) {
  var infoWindow = new google.maps.InfoWindow();
  var myLatLng = new google.maps.LatLng(site.Lat, site.Lng);
  var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    title: site['Name'],
    url: site['Website'],
    date: site['End Date'],
    address: await reverseGeoLookup(site)
  });

  google.maps.event.addListener(marker, 'click', function () {
    infoWindow.setContent(`<h3><a href=${this.url} target='_blank'>${this.title}</a></h3>${this.address}<br>date of event: ${this.date}`);
    infoWindow.open(map, this);
  });
}

// Save all the pen show addresses to local storage via reverse geo lookup api
async function reverseGeoLookup(site){
    let key = `${site.Lat},${site.Lng}`;
    let address;

    address = localStorage.getItem(key);

    if(address) 
      return address;
    else {
      var gRequestPromise = Promise.resolve($.getJSON(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${key}&key=${apiKey}`));

      gRequestPromise.then((value) => {
        address = value.results[0].formatted_address;
        localStorage.setItem( key, address);
      });
    }
  return address;
}

// build pen show map using json document for markers, and geo reverse api and local storage for addresses
async function initPenShowMap() {

  // load the pen show list from json
  const placesPromise = Promise.resolve($.getJSON('penshows.json'));
  
  placesPromise.then((value) => {
    places = value.shows;
  });
  
  const { Map } = await google.maps.importLibrary("maps");

  // set options for drawing map
  var mapOptions = {
    zoom: 2,
    center: new google.maps.LatLng(41.056466,-85.3312009),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  map2 = new google.maps.Map(document.getElementById('penShowMap'), mapOptions);

  // Get user selected filter from dropdown
  let filter = document.getElementById('showFilter').value;

  switch (filter) {
    case 'usShows':
      places.forEach( (site) => {
        if(site.Country == 'United States')
            addInfoWindowMarker(map2, site);  
      });

      //center the map IN US
      map2.setCenter(  { lat: 39.833333, lng: -98.585522 });
      map2.setZoom(4);
      break;

    case 'upcomingShows':          
    places.forEach( (site) => {
        if(new Date(site['End Date']) > new Date())
        addInfoWindowMarker(map2, site);  
      });
      break;

    default:
      places.forEach( (site) => {
        addInfoWindowMarker(map2, site);  
      });
  }

  /* If needed in future to get local location via ip address 
  const restPromise = Promise.resolve($.getJSON('https://ip.seeip.org/geoip'));
  
  restPromise.then((value) => {
    console.log(value);
    loc = {"Long": value.longitude, "Lat":value.latitude};
  });
*/
}
