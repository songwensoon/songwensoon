 /**
     * Retrieve JS translator initialization javascript
     *
     * @return string
     */
    public function getFrontTranslatorScript()
    {        
        $script = 'var Translator = {
				  data:'.$this->getTranslateJson().',
					initialize: function(data){
			        this.data = data;
			    },
			    translate : function(arguments){
			        var text = arguments[0];
				      text = this.data[text] || text;
			        if (arguments.length > 1) {
			            for (var i=1; i < arguments.length; i++) {
			                text = text.replace(/%s/, arguments[i]);
			            }
			        }
			        return text;
			    },
			    add : function() {
			        if (arguments.length > 1) {
			            this.data[arguments[0]] = arguments[1];
			        } else if (typeof arguments[0] ==\'object\') {
			        		for(var key in arguments[0]){
			        				if(arguments[0][key]){
			        					this.data[key] = arguments[0][key];
			        				}
			        		}
			        }
			    }
				};';
				$script .= "function __(){
								var args = arguments;
				    		return Translator.translate(args);
								}";
        return $this->getScript($script);
    }
    
