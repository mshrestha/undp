var KAZI=KAZI||{};KAZI.util=function(){};KAZI.map=function(){};
KAZI.map.util=function(){var zoom_level=6.8;var index_grades=[0,20,40,60,80,100,120,140];var map;var legend;var geojson;var info;var theme_color="#ffffff";var options={url:"/nepal"};var style={weight:1,opacity:1,color:"#5d5d5d",dashArray:"0",fillOpacity:.7,fillColor:"#000"};var init=function(){showMap();getMapData();showLabel()};var getMapData=function(){$.ajax({url:options.url,success:function(data){geojson=L.geoJson(data,{style:style,onEachFeature:onEachFeature}).addTo(map);return}})};var showMap=function(){map=L.map("map",{zoomControl:true,dragging:false}).setView([28.5,84.3],zoom_level);L.tileLayer("",{maxZoom:10,minZoom:6.5,id:"kazi-map"}).addTo(map);map.dragging.enable();legend=L.control({position:"bottomleft"});legend.onAdd=function(map){var div=L.DomUtil.create("div","info legend"),grades=index_grades,labels=[],from,to;labels.push('<div class="legend-title">Rate</div><div class="legend-wrap">');for(var i=0;i<grades.length;i++){from=grades[i];to=grades[i+1];labels.push('<i style="background:'+getColor(from+1)+'"></i> '+from+(to?"&ndash;"+to:"+"))}div.innerHTML=labels.join("<br>");labels.push("</div>");return div};legend.addTo(map);return};var showLabel=function(){info=L.control();info.onAdd=function(map){this._div=L.DomUtil.create("div","info");this.update();return this._div};info.update=function(props){this._div.innerHTML=props?'<div class="district-name">'+props.name+"</div><br />"+'<div class="girls item"><div class="label"><div class="icon"></div><div class="label-name">Girls</div></div><div class="value">'+props.girls+'%</div></div><div class="clear"></div>'+'<div class="boys item"><div class="label"><div class="icon"></div><div class="label-name">Boys</div></div><div class="value">'+props.boys+'%</div></div><div class="clear"></div>'+'<div class="total item"><div class="label"><div class="icon"></div><div class="label-name">Total</div></div><div class="value">'+props.total+'%</div></div><div class="clear"></div>':'<div class="hover-district">Hover over a district</div>'};info.addTo(map);return};var getColor=function(d){return d>1e3?"rgba("+theme_color+",1)":d>500?"rgba("+theme_color+",0.9)":d>200?"rgba("+theme_color+",0.8)":d>100?"rgba("+theme_color+",0.7)":d>50?"rgba("+theme_color+",0.6)":d>20?"rgba("+theme_color+",0.5)":d>10?"rgba("+theme_color+",0.4)":"#ccc"};var highlightFeature=function(e){var layer=e.target;layer.setStyle({weight:2,color:border_color,dashArray:"",fillOpacity:.7});if(!L.Browser.ie&&!L.Browser.opera){layer.bringToFront()}info.update(layer.feature.properties)};var geojson;var resetHighlight=function(e){geojson.resetStyle(e.target);info.update()};var zoomToFeature=function(e){map.fitBounds(e.target.getBounds())};var onEachFeature=function(feature,layer){layer.on({mouseover:highlightFeature,mouseout:resetHighlight})};return{showLabel:showLabel,showMap:showMap,init:init};this.init()}();
KAZI.util.loading=function(){var mainDiv="";var options={id:"loading"};var init=function(){createDivs()};var show=function(){$(mainDiv).show();return mainDiv};var hide=function(){$(mainDiv).hide();return mainDiv};var createDivs=function(){d=document.createElement("div");$(d).addClass("sk-spinner");$(d).addClass("sk-spinner-cube-grid");for(i=0;i<9;i++){s=document.createElement("div");$(s).addClass("sk-cube");$(d).append($(s))}spinner=document.createElement("div");$(spinner).addClass("spinner");$(spinner).append($(d));$(spinner).center();$(spinner).css("z-index",2041);bkdrop=document.createElement("div");$(bkdrop).addClass("backdrop");$(bkdrop).addClass("in");$(bkdrop).css("z-index",2040);$(bkdrop).css("position","fixed");$(bkdrop).css("top",0);$(bkdrop).css("right",0);$(bkdrop).css("bottom",0);$(bkdrop).css("left",0);$(bkdrop).css("background-color","#000");$(bkdrop).css("opacity","0.5");mainDiv=document.createElement("div");mainDiv.setAttribute("id",options.id);$(mainDiv).append($(spinner));$(mainDiv).append($(bkdrop));$("body").append($(mainDiv));$("#"+options.id).hide()};return{show:show,hide:hide,init:init};this.init()}();