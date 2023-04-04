**Watch the video to see how it works.**

Do you also love the simplicity of Express Data Objects but is the performance not satisfactory for your project? Or you annoyed that the data for an express entity is spread across many disparate tables in a way that makes it hard to see, fragile to breakage and tricky to repair?

These are now things of the past. Create Doctrine Entities with the same easy-to-use interface you are used to in modelling Express Data Objects, straight from your concrete5 dashboard. With Entity Designer, an entity's data data maps directly onto records in an associated database table. As a developer, you can see exactly where your data is. 

You can create entities and then extend them by adding any input field. As soon as you hit save, the add-on completes the rest for you. The entity, controller, detail view, and list view are automatically generated and installed in the background. The code is generated according to the best coding guidelines of concrete5.

You can apply changes at any time. The files and the database schema are updated automatically. If you want to see how the generated data looks please click [here](https://www.concrete5.org/marketplace/addons/entity-designer/documentation/).

This add-on is a real time-safer. Because modelling the entities and the associated views for editing and creating records is the same procedure each time. However, these tasks are not easy to reproduce. This is precisely the reason why this add-on was developed. Save your precious time for the valuable things in life and design your Doctrine Entities with this add-on instead of tediously programming everything yourself manually.

Of course you can extend the generated files with your own program code and use the generated code as a template.

As a special highlight, this add-on automatically generates all frontend blocks for your entity. So you can integrate the data directly into your website without any coding knowledges. For each entity, one block is generated for the list view, another one for the detailed view and another one with a form so that data can also save entries to the the entity directly via the frontend. The options are similar to the Core Express blocks. So you have e.g. the possibility to automatically send an email after successfully submitting a form, to integrate CAPTCHA protection and much more.

You can either have the entities created in the application directory or in any package directory. This is especially useful for package developers.

The following input fields are currently being available:

* File (The concrete5 file selector is used)
* Page (The concrete5 page selector is used)
* Text
* Email
* Phone
* Website
* Password
* Text field
* Radio buttons
* Checkbox list
* Selectbox
* Associations (1:1, 1:n, n:m)

All input fields have attributes which you can use to influence the field type, but also to determine how the input field is to be displayed later via the Look n' Feel. Even bootstrap input groups including prefix and suffix are supported.

In addition, you can also define field-specific attributes, such as the attributes min, max and step for the number input field.

I plan to support more fields and attributes in the near future. If you need a specific input field or have other useful ideas, please contact me.

**Important Notices:**

The following directories needs to be writable:

* application/controllers
* application/single_pages
* application/src
* application/elements
* application/blocks
* packages