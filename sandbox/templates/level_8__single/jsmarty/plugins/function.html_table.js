function jsmarty_function_html_table(G,N){if(!G.loop){N.trigger_error("html_table: missing 'loop' parameter");return ;}var Y,V,D,S,f,C;var F=new JSmarty.Classes.Buffer();var O=jsmarty_function_html_table_cycle;var y=G.loop;var j=3;var H=3;var w="down";var I="right";var Z="cols";var A="";var d="";var s="&nbsp;";var q=" border=\"1\"";var i=y.length;for(S in G){D=G[S];switch(S){case "loop":y=D;break;case "cols":j=parseInt(D);break;case "rows":H=parseInt(D);break;case "hdir":I=D;break;case "vdir":w=D;break;case "inner":Z=D;break;case "trailpad":s=D;break;case "table_attr":q=" "+D;break;case "tr_attr":A=D;break;case "td_attr":d=D;break;}}if(G.rows==void (0)){H=Math.ceil(i/j);}if(G.cols==void (0)){j=Math.ceil(i/H);}F.append("<table",q,">");for(V=0;V<H;V++){F.append("<tr",O("tr",A,V),">");C=(w=="down")?V*j:(H-1-V)*j;for(Y=0;Y<j;Y++){f=(I=="right")?C+Y:C+j-1-Y;if(Z!="cols"){f=Math.floor(f/j)+(f%j)*H;}if(f<i){F.append("<td",O("td",d,Y),">",y[f],"</td>");}else{F.append("<td",O("td",d,Y),">",s,"</td>");}}F.append("</tr>");}F.append("</table>");return F.toString("\n");}function jsmarty_function_html_table_cycle(i,d,i){var I=(d instanceof Array)?d[i%d.length]:d;return (I)?" "+I:"";}