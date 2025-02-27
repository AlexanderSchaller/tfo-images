# Image Server for The Final Outpost

[The Final Outpost](https://finaloutpost.net) is an online collecting game where users "adopt" a variety of creatures.  
The creatures feature generative art based on the genetics how a creature is spawned.

For example, a creature might have the following genes ``Body:aa, Markings:AA, Spots:AA`` and look like:
![](.repo-images/Bodyaa-MarkingsAA-SpotsAA-1.png)

Another creature might have ``Body:aa, Markings:aa, Spots:aa``
![](.repo-images/Bodyaa-Markingsaa-Spotsaa-2.png)

While a third may be ``Body:aa, Markings:Aa, Spots:Aa``
![](.repo-images/Bodyaa-MarkingsAa-SpotsAa-3.png)

While a limited number of genes keeps permutations manageable, species are not restricted to a fixed gene count. 
Dimorphism can also double the amount of art needed. Some 
can have up to 14 billion possible combinations, making it mathematically impossible for artists to manually create every variation. 
Additionally, certain species require special layering—such as placing a back wing at the bottom and a front wing at the 
top, even though the database categorizes both simply as "wing." The need for extra image flags further complicates 
clean, efficient coding.  Additionally, the code must be able to assemble the images on the fly for a high amount of 
traffic.

This server solves those issues by breaking species into specific "compositions" that dictate how it should be rendered.  
It also makes sure that images are cached and rendered effectively for different environments (for example on site vs a
twitter meta image card).
