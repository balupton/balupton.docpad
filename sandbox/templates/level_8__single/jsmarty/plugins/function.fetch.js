var jsmarty_function_fetch_caches={};function jsmarty_function_fetch(k,q){var i,I,d=jsmarty_function_fetch_caches;if(!("file" in k)){q.trigger_error("fetch : parameter \"file\" cannot be empty","die");return ;}i=q.get_resource_name(k.file);I=d[i]||function(){d[i]=new JSmarty.Classes.Item(i);return d[i].load(q);}();if(k.assign){q.assign(k.assign,I.get("src"));return ;}return I.get("src");}