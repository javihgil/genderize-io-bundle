# GenderizeIoBundle

This bundle integrates GenderizeIoClient into Symfony Projects.

## Configure


## Usage from a controller

    /** @var Jhg\GenderizeIoClient\Model\Name $name */
    $name = $this->get('genderizer')->recognize('John');
    echo $name->getGender();
    // shows "male"
    
    
## Usage from twig

    {{ 'John' | gender }}
    {# paints "male" #}

    {{ 'John' | genderInCountry('gb') }}
    {# paints "male" #}

    {{ 'John' | genderInLanguage('en') }}
    {# paints "male" #}
    
    {# consider using default filter after gender function for unknown cases #}
    {{ 'Unknown Name' | gender | default('no genre') }}
    {# paints "no gerne" #}    
    
    {% if isMale('John') %}
        {# do something for male #}
    {% else isFemale('John') %}
        {# do something for male #}
    {% else %}
        {# do something for unknown cases #}
    {% endif %}